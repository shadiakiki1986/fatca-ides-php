<?php

namespace FatcaIdesPhp;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Transmitter {

	var $fdi; // php data with fatca information
	var $dataXml;
	var $dataXmlSigned;
	var $dataCompressed;
	var $dataEncrypted;
	var $diDigest;
	var $aeskey;
	var $tf3;
	var $tf4;
	var $aesEncrypted;
	var $file_name;

	function __construct($fdi,$conMan,$LOG_LEVEL=Logger::WARNING) {
	// fdi: object of type implementing FatcaDataInterface
  // conMan: object of type ConfigManager

    $gcf=get_class($fdi);
    $cig=class_implements($gcf);
    $tobe="FatcaIdesPhp\FatcaDataInterface";
    assert(
      in_array($tobe, $cig),
      "Input type test failed. Class ".$gcf." implements ".implode(", ",$cig)." but not ".$tobe);
		$this->fdi=$fdi;

    assert($conMan instanceOf ConfigManager);
    $this->conMan=$conMan;

		// reserving some filenames
		$this->tf3=tempnam("/tmp","");
		$this->tf4=tempnam("/tmp","");

    $this->log = new Logger('Transmitter');
    // http://stackoverflow.com/a/25787259/4126114
    $this->log->pushHandler(new StreamHandler('php://stdout', $LOG_LEVEL)); // <<< uses a stream
    $this->LOG_LEVEL=$LOG_LEVEL;

		$this->file_name = strftime("%Y%m%d%H%M%S00%Z",$this->fdi->getTsBase())."_".$this->fdi->getGiinSender().".zip";
  }

  function start() {
    $this->conMan->check();
    $this->fdi->start();
	}

	function toXml($utf8=false) {
    $this->dataXml=$this->fdi->toXml($utf8);
	}

	function validateXml($whch="payload") {
		# validate
		$xml="";
		$xsd="";
		switch($whch) {
		case "payload":
			$xml=$this->dataXml;
			$xsd=$this->conMan->config["FatcaXsd"];
			break;
		case "metadata":
			$xml=$this->getMetadata();
			$xsd=$this->conMan->config["MetadataXsd"];
			break;
		default: throw new \Exception("Invalid xml file to validate");
		}

    $doc = new \DOMDocument();
		$xmlDom=$doc->loadXML($xml);
    if(!$xmlDom) throw new \Exception(sprintf("Invalid XML: %s",$xml));
		return $doc->schemaValidate($xsd);
	}


	function toXmlSigned() {
		$sm=new SigningManager($this->conMan);
		$this->dataXmlSigned = $sm->sign($this->dataXml);
	}

	function verifyXmlSigned() {
		$sm=new SigningManager($this->conMan);
		return $sm->verify($this->dataXmlSigned);
	}

	function toCompressed() {
		$zip = new \ZipArchive();
		$filename = $this->tf3;

		if ($zip->open($filename, \ZipArchive::CREATE)!==TRUE) {
		    exit("cannot open <$filename>\n");
		}

		$zip->addFromString($this->fdi->getGiinSender()."_Payload.xml", $this->dataXmlSigned);
		$zip->close();

		$this->dataCompressed=file_get_contents($this->tf3);
	}

	function toEncrypted() {
		$am=new AesManager();
		$this->aeskey = $am->aeskey;
		$this->dataEncrypted=$am->encrypt($this->dataCompressed);
	}

	function readIrsPublicKey($returnResource=true) {
	  $fp=fopen($this->conMan->config["FatcaIrsPublic"],"r");
	  $pub_key_string=fread($fp,8192);
	  fclose($fp);
	  if($returnResource) {
		$pub_key="";
		$pub_key=openssl_get_publickey($pub_key_string); 
		return $pub_key;
	  } else {
		return $pub_key_string;
	  }
	}

	function encryptAesKeyFile() {
		$this->aesEncrypted="";
		if(!openssl_public_encrypt ( $this->aeskey , $this->aesEncrypted , $this->readIrsPublicKey() )) {
      throw new \Exception("Did not encrypt aes key");
    }
		if($this->aesEncrypted=="") throw new \Exception("Failed to encrypt AES key");
	}

	function verifyAesKeyFileEncrypted() {
		$pubk=$this->readIrsPublicKey(true);
		$decrypted="";
		if(!openssl_public_decrypt( $this->aesEncrypted , $decrypted , $pubk )) throw new \Exception("Failed to decrypt aes key for verification purposes");
		return($decrypted==$this->aeskey);
	}

	function getMetadata() {
		// ts2 is xsd:dateTime
		// http://www.datypic.com/sc/xsd/t-xsd_dateTime.html
		// Even though the xsd:dateTime supports dates without a timezone,
		// dropping the Z from here causes the metadata file not to pass the schema
		// (and a RC004 to be received instead of RC001)
    $ts2=strftime("%Y-%m-%dT%H:%M:%SZ",$this->fdi->getTsBase());

    /*
    // This is probably unnecessary
    $md='<?xml version="1.0" encoding="utf-8"?>
    */
    $md='<FATCAIDESSenderFileMetadata xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="urn:fatca:idessenderfilemetadata">
      <FATCAEntitySenderId>'.$this->fdi->getGiinSender().'</FATCAEntitySenderId>
      <FATCAEntityReceiverId>'.$this->getGiinReceiver().'</FATCAEntityReceiverId>
      <FATCAEntCommunicationTypeCd>RPT</FATCAEntCommunicationTypeCd>
      <SenderFileId>'.rand(1,9999999).'</SenderFileId>
      <FileCreateTs>'.$ts2.'</FileCreateTs>
      <TaxYear>'.$this->fdi->getTaxYear().'</TaxYear>
      <FileRevisionInd>false</FileRevisionInd>
    </FATCAIDESSenderFileMetadata>';
    // drop all spaces
    // This is probably unnecessary
    $doc = new \DOMDocument();
    $doc->preserveWhiteSpace = false;
    $doc->formatOutput = false;
    $doc->loadXML($md);
    $md=$doc->saveXML();

    return $md;
	}

	function toZip($includeUnencrypted=false) {
		$zip = new \ZipArchive();
		unlink($this->tf4);
		$filename = $this->tf4;

		if ($zip->open($filename, \ZipArchive::CREATE)!==TRUE) {
		    exit("cannot open <$filename>\n");
		}

		$zip->addFromString(
      $this->fdi->getGiinSender()."_Payload",
      $this->dataEncrypted);
		$zip->addFromString(
      $this->getGiinReceiver()."_Key",
      $this->aesEncrypted);
		$zip->addFromString(
      $this->fdi->getGiinSender()."_Metadata.xml",
      $this->getMetadata());

		if($includeUnencrypted) {
			$zip->addFromString(
        $this->fdi->getGiinSender()."_Payload_unencrypted.xml",
        $this->dataXmlSigned);
		}

		$zip->close();
	
	}

	function getZip() {
		// or however you get the path
		$yourfile = $this->tf4;
		date_default_timezone_set("UTC");

		header("Content-Type: application/zip");
		header("Content-Disposition: attachment; filename=".$this->file_name);
		header("Content-Length: " . filesize($yourfile));

		readfile($yourfile);
	}

  public function toEmail($emailTo,$emailFrom,$emailName,$emailReply,$swiftmailerConfig=array()) {
    // save to files
    $fnH = Utils::myTempnam('html');
    file_put_contents($fnH,$this->fdi->toHtml());
    $fnX = Utils::myTempnam('xml');
    file_put_contents($fnX,$this->dataXmlSigned);
    $fnM = Utils::myTempnam('xml');
    file_put_contents($fnM,$this->getMetadata());
    $fnZ1 = Utils::myTempnam('zip');
    copy($this->tf4,$fnZ1);

    // zip to avoid getting blocked on server
    $z = new \ZipArchive();
    $fnZ2 = Utils::myTempnam('zip');
    $z->open($fnZ2, \ZIPARCHIVE::CREATE);
    $z->addEmptyDir("IDES data");
    $z->addFile($fnH, "IDES data/data.html");
    $z->addFile($fnX, "IDES data/data.xml");
    $z->addFile($fnM, "IDES data/metadata.xml");
    $z->addFile($fnZ1, "IDES data/data.zip");
    $z->close(); 

    // send email
    $subj=sprintf("IDES data: %s",date("Y-m-d H:i:s"));

    $this->log->debug("Sending attachment email");
    $res = Utils::mail_attachment(
      array($fnZ2),
      $emailTo,
      $emailFrom, // from email
      $emailName, // from name
      $emailReply, // reply to
      $subj." (attachment)", 
      "Attached: html, xml, metadata, zip formats",
      $swiftmailerConfig
    );
    if(!$res) throw new \Exception("Failed to send attachment email. Aborting.");
    $this->log->debug("Done emailing");
  } // end function toEmail

  public function toUpload($upload,$swiftmailerConfig=null) {
    if(is_null($upload)) return;
    assert(is_array($upload) && array_key_exists("username",$upload) && array_key_exists("password",$upload));

    $sftp = SftpWrapper::getSFTP($this->fdi->getIsTest()?"test":"live");
    $sw = new SftpWrapper($sftp,$this->LOG_LEVEL);

    $err = $sw->login($upload["username"],$upload["password"]);
    if(!!$err) {
      if(!is_null($swiftmailerConfig)) {
        Utils::mail_wrapper(
          $emailTo, $emailFrom, $emailName, $emailReply, 
          $subj." (upload error login)", $err,
          $swiftmailerConfig);
      }
      throw new \Exception($err);
    }

    $err = $sw->put($this->tf4,$this->file_name);
    #$err = "Uploading currently disabled";
    if(!!$err) {
      if(!is_null($swiftmailerConfig)) {
        Utils::mail_wrapper(
          $emailTo, $emailFrom, $emailName, $emailReply, 
          $subj." (upload error file)",
          $err,
          $swiftmailerConfig);
      }
      throw new \Exception($err);
    }

    $msg = "Succeeded in uploading zip file";
    if(!is_null($swiftmailerConfig)) {
      Utils::mail_wrapper(
        $emailTo, $emailFrom, $emailName, $emailReply, 
        $subj." (upload success)",
        $msg,
        $swiftmailerConfig);
    }
    $this->log->info($msg);
  }


  // Shortcut that takes input for FatcaDataArray + Transmitter
  public static function shortcut($fdi,$format,$emailTo,$config,$LOG_LEVEL=Logger::WARNING) {
    $dm = new Downloader(null,$LOG_LEVEL);
    $conMan = new ConfigManager($config,$dm,$LOG_LEVEL);

    $tmtr=new Transmitter($fdi,$conMan,$LOG_LEVEL);
    $tmtr->start();
    $tmtr->toXml(); # convert to xml 

    if(!$tmtr->validateXml("payload")) {# validate
      print 'Payload xml did not pass its xsd validation';
      Utils::libxml_display_errors();
      $exitCond=in_array($format,array("xml","zip"));
      $exitCond=$exitCond||$emailTo;
      if($exitCond) exit;
    }

    if(!$tmtr->validateXml("metadata")) {# validate
        print 'Metadata xml did not pass its xsd validation';
        Utils::libxml_display_errors();
        exit;
    }

    $tmtr->toXmlSigned();
    if(!$tmtr->verifyXmlSigned()) die("Verification of signature failed");

    $tmtr->toCompressed();
    $tmtr->toEncrypted();
    $tmtr->encryptAesKeyFile();
    //	if(!$tmtr->verifyAesKeyFileEncrypted()) die("Verification of aes key encryption failed");
    $tmtr->toZip(true);
    if(array_key_exists("ZipBackupFolder",$config)) {
      $fnDest=$config["ZipBackupFolder"]."/includeUnencrypted_".$tmtr->file_name;
      copy($tmtr->tf4,$fnDest);
    }
    $tmtr->toZip(false);
    if(array_key_exists("ZipBackupFolder",$config)) {
      $fnDest=$config["ZipBackupFolder"]."/submitted_".$tmtr->file_name;
      copy($tmtr->tf4,$fnDest);
    }

    return $tmtr;
  }

  function getGiinReceiver() { return $this->conMan->config["ffaidReceiver"]; }

} // end class
