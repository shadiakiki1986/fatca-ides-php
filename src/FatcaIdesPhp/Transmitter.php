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
	var $tf3;
	var $tf4;
	var $file_name;

	/*
   * fdi: object of type implementing FatcaDataInterface
   * conMan: object of type ConfigManager
   * rm: RsaManager
   */

	function __construct(FatcaDataInterface $fdi, $conMan, $rm, $LOG_LEVEL=Logger::WARNING) {
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

		$this->file_name = strftime("%Y%m%d%H%M%S000%Z",$this->fdi->getTsBase())."_".$this->fdi->getGiinSender().".zip";

    assert($rm instanceOf RsaManager);
    $this->rm=$rm;

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
		$this->dataEncrypted=$this->rm->am->encrypt($this->dataCompressed);
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
      $this->rm->aesEncrypted);
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

  public static function verifySwiftmailerConfig($smc) {        
    assert(is_array($smc)); 
    $fields = array("host","port","username","password","name","reply");
    foreach($fields as $fx) {
      if(!array_key_exists($fx,$smc)) {
        throw new \Exception("swiftmailer field in config missing field: ".$fx);
      }
    }
  }

  public function toEmail($emailTo,$swiftmailerConfig) {
    Transmitter::verifySwiftmailerConfig($swiftmailerConfig);

    $emailFrom = $swiftmailerConfig["reply"];                   
    $emailName = $swiftmailerConfig["name"];                    
    $emailReply =$swiftmailerConfig["reply"];                   

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

  public function toUpload($upload,$emailTo=null,$swiftmailerConfig=null) {
    if(is_null($upload)) return;
    assert(is_array($upload) && array_key_exists("username",$upload) && array_key_exists("password",$upload));
    assert(!(is_null($emailTo) xor is_null($swiftmailerConfig)));

    $emailFrom = "";
    $emailName = "";
    $emailReply = "";

    if(!is_null($swiftmailerConfig)) {
      Transmitter::verifySwiftmailerConfig($swiftmailerConfig);

      $emailFrom = $swiftmailerConfig["reply"];                   
      $emailName = $swiftmailerConfig["name"];                    
      $emailReply =$swiftmailerConfig["reply"];                   
    }

    $sftp = SftpWrapper::getSFTP($this->fdi->getIsTest()?"test":"live");
    $sw = new SftpWrapper($sftp,$this->LOG_LEVEL);

    $subj=sprintf("IDES data: %s",date("Y-m-d H:i:s"));

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

  function getGiinReceiver() { return $this->conMan->config["ffaidReceiver"]; }

} // end class
