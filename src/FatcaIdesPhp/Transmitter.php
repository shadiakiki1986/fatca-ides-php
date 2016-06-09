<?php

namespace FatcaIdesPhp;

use Monolog\Logger;

class Transmitter {

	var $data; // php data with fatca information
	var $dataXml;
	var $dataXmlSigned;
	var $dataCompressed;
	var $dataEncrypted;
	var $diDigest;
	var $aeskey;
	var $tf1;
	var $tf2;
	var $tf3;
	var $tf4;
	var $aesEncrypted;
	var $ts;
	var $ts2;
	var $ts3;
	var $file_name;
	var $docType;
	var $corrDocRefId;
	var $taxYear;
	var $guidManager;

	function __construct($dd,$isTest,$corrDocRefId,$taxYear,$conMan) {
	// dd: 2d array with fatca-relevant fields
	// isTest: true|false whether the data is test data. This will only help set the DocTypeIndic field in the XML file
	// corrDocRefId: false|message ID. If this is a correction of a previous message, pass the message ID in subject, otherwise just pass false

    assert($conMan instanceOf ConfigManager);
    $this->conMan=$conMan;

		$this->data=$dd;
		$this->corrDocRefId=$corrDocRefId;
		$this->isTest=$isTest;
		$this->taxYear=$taxYear;
  }

  function start() {
    $this->conMan->check();

		$this->docType=sprintf("FATCA%s%s",$this->isTest?"1":"",$this->corrDocRefId?"2":"1");

		// Sanity check
		// docType: described in xsd for DocRefId
		// FATCA1: New Data, FATCA2: Corrected Data, FATCA3: Void Data, FATCA4: Amended Data
		// FATCA11: New Test Data, FATCA12: Corrected Test Data, FATCA13: Void Test Data, FATCA14: Amended Test Data
		$docTypeValid=array("FATCA11","FATCA12","FATCA13","FATCA14","FATCA1","FATCA2","FATCA3","FATCA4");
		if(!in_array($this->docType,$docTypeValid)) throw new \Exception("Unsupported docType. Please use: ".implode(", ",$docTypeValid));


		// following http://www.irs.gov/Businesses/Corporations/FATCA-XML-Schema-Best-Practices-for-Form-8966
		// the hash in an address should be replaced
		$this->data=array_map(function($x) {
			$reps=array(":","#",",","-",".","--","/");
			$x['ENT_ADDRESS']=str_replace($reps," ",$x['ENT_ADDRESS']);
			$x['ENT_ADDRESS']=preg_replace('/\s\s+/',' ',$x['ENT_ADDRESS']);
			$x['ENT_ADDRESS']=str_replace("1st","First",$x['ENT_ADDRESS']);
			$x['ENT_ADDRESS']=str_replace("9th","Ninth",$x['ENT_ADDRESS']);
			$x['ENT_ADDRESS']=str_replace("6th","Sixth",$x['ENT_ADDRESS']);
			$x['ENT_ADDRESS']=str_replace("6TH","Sixth",$x['ENT_ADDRESS']);
			$x['ENT_ADDRESS']=str_replace("3rd","Third",$x['ENT_ADDRESS']);
			$x['ENT_ADDRESS']=str_replace("8TH","Eighth",$x['ENT_ADDRESS']);
			$x['ENT_FATCA_ID']=str_replace($reps," ",$x['ENT_FATCA_ID']);
			$x['ENT_FATCA_ID']=str_replace(array("S","N"," "),"",$x['ENT_FATCA_ID']);
			return $x;
		}, $this->data);

		// reserving some filenames
		$this->tf1=tempnam("/tmp","");
		$this->tf2=tempnam("/tmp","");
		$this->tf3=tempnam("/tmp","");
		$this->tf4=tempnam("/tmp","");

    //Code playing with timezone
    //    date_default_timezone_set('UTC');
    //    $ts1=time();
    //    $ts2=strftime("%Y-%m-%dT%H:%M:%SZ",$ts1); 
    //    $tz=date_default_timezone_get();
    //    #var_dump($ts1,$ts2,$tz);
    //    $this->assertTrue($tz=="UTC");
		date_default_timezone_set('UTC');
		$this->ts=time();
		// ts2 is xsd:dateTime
		// http://www.datypic.com/sc/xsd/t-xsd_dateTime.html
		// Even though the xsd:dateTime supports dates without a timezone,
		// dropping the Z from here causes the metadata file not to pass the schema
		// (and a RC004 to be received instead of RC001)
		$this->ts2=strftime("%Y-%m-%dT%H:%M:%SZ",$this->ts); 
		$this->ts3=strftime("%Y-%m-%dT%H:%M:%S", $this->ts); 

		// prepare guids to use
		$this->guidManager=new GuidManager();
	}

	function toHtml() {
		$dv=array_values($this->data);
    $headers=array("ENT_LASTNAME","ENT_FIRSTNAME","ENT_FATCA_ID","ENT_ADDRESS","ResidenceCountry","ENT_COD","posCur","Compte","cur","dvdCur","intCur");

    // assert that there no keys more than those defined above
    array_map(function($x) use($headers) {
      assert(count(array_diff(array_keys($x),$headers))==0);
    }, $this->data);

	  $th=implode(array_map(function($x) { return "<th>".$x."</th>"; },$headers));
    $body=array();
    foreach($this->data as $y) {
      $row=array();
      foreach($headers as $x1) {
        if(array_key_exists($x1,$y)) {
          $x2=$y[$x1];
          if(!is_array($x2)) {
            array_push($row, "<td>".$x2."</td>");
          } else {
            die("arrays here No longer supported");
          }
        } else {
          array_push($row,"<td>&nbsp;</td>");
        }
      }
      $row=implode($row);
      array_push($body, "<tr>".$row."</tr>");
    }
    $body=implode($body);

		$html2 = sprintf("<table border=1>%s%s</table>\n",$th,$body);

    // pretty print html
    $doc = new \DOMDocument();
    $doc->preserveWhiteSpace = true;
    $doc->formatOutput = true;//false;
    $doc->loadHTML($html2);
    $html3=$doc->saveHTML();
    return $html3;
	}

	function toXml($utf8=false) {
	    $di=$this->data; # $di: output of getFatcaClients
	    $docType=$this->docType;

	    # convert to xml 
	    #        xsi:schemaLocation='urn:oecd:ties:fatca:v1 FatcaXML_v1.1.xsd'
	    $gm=$this->guidManager;
	    $diXml=sprintf("
		<ftc:FATCA_OECD version='1.1'
		    xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance'
		    xmlns:sfa='urn:oecd:ties:stffatcatypes:v1'
		    xmlns:ftc='urn:oecd:ties:fatca:v1'>
		    <ftc:MessageSpec>
			<sfa:SendingCompanyIN>".$this->conMan->config["ffaid"]."</sfa:SendingCompanyIN>
			<sfa:TransmittingCountry>LB</sfa:TransmittingCountry>
			<sfa:ReceivingCountry>US</sfa:ReceivingCountry>
			<sfa:MessageType>FATCA</sfa:MessageType>
			<sfa:Warning/>
			<sfa:MessageRefId>%s</sfa:MessageRefId>
			<sfa:ReportingPeriod>".sprintf("%s-12-31",$this->taxYear)."</sfa:ReportingPeriod>
			<sfa:Timestamp>$this->ts3</sfa:Timestamp>
		    </ftc:MessageSpec>
		    <ftc:FATCA>
			<ftc:ReportingFI>
			    <sfa:Name>FFA Private Bank</sfa:Name>
			    <sfa:Address>
				<sfa:CountryCode>LB</sfa:CountryCode>
				<sfa:AddressFree>Foch street</sfa:AddressFree>
			    </sfa:Address>
			    <ftc:DocSpec>
				<ftc:DocTypeIndic>%s</ftc:DocTypeIndic>
				<ftc:DocRefId>%s</ftc:DocRefId>
				%s
			    </ftc:DocSpec>
			</ftc:ReportingFI>
		    <ftc:ReportingGroup>
		    %s
		    </ftc:ReportingGroup>
		    </ftc:FATCA>
		</ftc:FATCA_OECD>",
		$this->guidManager->guidPrepd[0], // specifically indexing guidPrepd instead of using the get function so as to get the same ID if I run this function twice
		$docType, // not sure about this versus the same entry below
		//sprintf("%s.%s",ffaid,$this->guidManager->guidPrepd[1]), // based on http://www.irs.gov/Businesses/Corporations/FATCA-XML-Schemas-Best-Practices-for-Form-8966-DocRefID
		$this->guidManager->guidPrepd[2],
		!$this->corrDocRefId?"":sprintf("<ftc:CorrDocRefId>%s</ftc:CorrDocRefId>",$this->corrDocRefId), 
		implode(array_map(
		    function($x) use($docType,$gm) {
			// TIN default  issuedBy='US'
			return sprintf("
			    <ftc:AccountReport>
			    <ftc:DocSpec>
			    <ftc:DocTypeIndic>%s</ftc:DocTypeIndic>
			    <ftc:DocRefId>%s</ftc:DocRefId>
			    </ftc:DocSpec>
			    <ftc:AccountNumber>%s</ftc:AccountNumber>
			    <ftc:AccountHolder>
			    <ftc:Individual>
				<sfa:TIN>%s</sfa:TIN>
				<sfa:Name>
				    <sfa:FirstName>%s</sfa:FirstName>
				    <sfa:LastName>%s</sfa:LastName>
				</sfa:Name>
				<sfa:Address>
				    <sfa:CountryCode>%s</sfa:CountryCode>
				    <sfa:AddressFree>%s</sfa:AddressFree>
				</sfa:Address>
			    </ftc:Individual>
			    </ftc:AccountHolder>
			    <ftc:AccountBalance currCode='%s'>%s</ftc:AccountBalance>
          %s
          %s
			    </ftc:AccountReport>
			",
			$docType, // check the xsd
			//sprintf("%s.%s",ffaid,$gm->guidPrepd[3]), // based on http://www.irs.gov/Businesses/Corporations/FATCA-XML-Schemas-Best-Practices-for-Form-8966-DocRefID
			$gm->guidPrepd[4],
			$x['Compte'],
			$x['ENT_FATCA_ID'],
			$x['ENT_FIRSTNAME'],
			$x['ENT_LASTNAME'],
			$x['ResidenceCountry'],
			$x['ENT_ADDRESS'],
			$x['cur'],
			$x['posCur'],
      array_key_exists('dvdCur',$x)?sprintf("<ftc:Payment><ftc:Type>FATCA501</ftc:Type><ftc:PaymentAmnt currCode='%s'>%s</ftc:PaymentAmnt></ftc:Payment>",$x['cur'],floor($x['dvdCur'])):"",
      array_key_exists('intCur',$x)?sprintf("<ftc:Payment><ftc:Type>FATCA502</ftc:Type><ftc:PaymentAmnt currCode='%s'>%s</ftc:PaymentAmnt></ftc:Payment>",$x['cur'],floor($x['intCur'])):""
			); },
		    $di
		),"\n")
	    );

		// utf8
		if($utf8) $diXml=utf8_encode($diXml);

		// drop all spaces ... 
		// This is probably unnecessary, but I had thought that the security threat alert we were receiving was because of this
		// I later learned that this was not the reason, but I'm leaving it anyway
		$doc = new \DOMDocument();
		$doc->preserveWhiteSpace = false;
		$doc->formatOutput = true;//false;
		$doc->loadXML($diXml);
		$diXml=$doc->saveXML();

	    $this->dataXml=$diXml;
	    return $diXml;
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

		$zip->addFromString($this->conMan->config["ffaid"]."_Payload.xml", $this->dataXmlSigned);
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
		$this->file_name = strftime("%Y%m%d%H%M%S00%Z",$this->ts)."_".$this->conMan->config["ffaid"].".zip";

		/*
		// This is probably unnecessary
		$md='<?xml version="1.0" encoding="utf-8"?>
		*/
		$md='<FATCAIDESSenderFileMetadata xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="urn:fatca:idessenderfilemetadata">
			<FATCAEntitySenderId>'.$this->conMan->config["ffaid"].'</FATCAEntitySenderId>
			<FATCAEntityReceiverId>'.$this->conMan->config["ffaidReceiver"].'</FATCAEntityReceiverId>
			<FATCAEntCommunicationTypeCd>RPT</FATCAEntCommunicationTypeCd>
			<SenderFileId>'.rand(1,9999999).'</SenderFileId>
			<FileCreateTs>'.$this->ts2.'</FileCreateTs>
			<TaxYear>'.$this->taxYear.'</TaxYear>
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
      $this->conMan->config["ffaid"]."_Payload",
      $this->dataEncrypted);
		$zip->addFromString(
      $this->conMan->config["ffaidReceiver"]."_Key",
      $this->aesEncrypted);
		$zip->addFromString(
      $this->conMan->config["ffaid"]."_Metadata.xml",
      $this->getMetadata());

		if($includeUnencrypted) {
			$zip->addFromString(
        $this->conMan->config["ffaid"]."_Payload_unencrypted.xml",
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

  public static function toEmail($fca,$emailTo,$emailFrom,$emailName,$emailReply) {
    // save to files
    $fnH = Utils::myTempnam('html');
    file_put_contents($fnH,$fca->toHtml());
    $fnX = Utils::myTempnam('xml');
    file_put_contents($fnX,$fca->dataXmlSigned);
    $fnM = Utils::myTempnam('xml');
    file_put_contents($fnM,$fca->getMetadata());
    $fnZ = Utils::myTempnam('zip');
    copy($fca->tf4,$fnZ);

    // zip to avoid getting blocked on server
    $z = new \ZipArchive();
    $fnZ2 = Utils::myTempnam('zip');
    $z->open($fnZ2, \ZIPARCHIVE::CREATE);
    $z->addEmptyDir("IDES data");
    $z->addFile($fnH, "IDES data/data.html");
    $z->addFile($fnX, "IDES data/data.xml");
    $z->addFile($fnM, "IDES data/metadata.xml");
    $z->addFile($fnZ, "IDES data/data.zip");
    $z->close(); 

    // send email
    $subj=sprintf("IDES data: %s",date("Y-m-d H:i:s"));

    if(!Utils::mail_attachment(
      array($fnZ2),
      $emailTo,
      $emailFrom, // from email
      $emailName, // from name
      $emailReply, // reply to
      $subj, 
      "Attached: html, xml, metadata, zip formats"
    )) throw new \Exception("Failed to send email");
  }

  public static function shortcut($di,$shuffle,$corrDocRefId,$taxYear,$format,$emailTo,$config,$LOG_LEVEL=Logger::WARNING) {
    if(count($di)==0) throw new \Exception("No data");
    if($shuffle) {
      // shuffle all fields except these... ,"Compte"
      $fieldsNotShuffle = array("ResidenceCountry","posCur","cur");
      $di=Utils::array2shuffledLetters($di,$fieldsNotShuffle); 
    }

    $dm = new Downloader(null,$LOG_LEVEL);
    $conMan = new ConfigManager($config,$dm,$LOG_LEVEL);

    $fca=new Transmitter($di,$shuffle,$corrDocRefId,$taxYear,$conMan);
    $fca->start();
    $fca->toXml(); # convert to xml 

    if(!$fca->validateXml("payload")) {# validate
      print 'Payload xml did not pass its xsd validation';
      Utils::libxml_display_errors();
      $exitCond=in_array($format,array("xml","zip"));
      $exitCond=$exitCond||$emailTo;
      if($exitCond) exit;
    }

    if(!$fca->validateXml("metadata")) {# validate
        print 'Metadata xml did not pass its xsd validation';
        Utils::libxml_display_errors();
        exit;
    }

    $fca->toXmlSigned();
    if(!$fca->verifyXmlSigned()) die("Verification of signature failed");

    $fca->toCompressed();
    $fca->toEncrypted();
    $fca->encryptAesKeyFile();
    //	if(!$fca->verifyAesKeyFileEncrypted()) die("Verification of aes key encryption failed");
    $fca->toZip(true);
    if(array_key_exists("ZipBackupFolder",$config)) {
      $fnDest=$config["ZipBackupFolder"]."/includeUnencrypted_".$fca->file_name;
      copy($fca->tf4,$fnDest);
    }
    $fca->toZip(false);
    if(array_key_exists("ZipBackupFolder",$config)) {
      $fnDest=$config["ZipBackupFolder"]."/submitted_".$fca->file_name;
      copy($fca->tf4,$fnDest);
    }

    return $fca;
  }
} // end class
