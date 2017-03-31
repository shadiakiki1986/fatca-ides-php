<?php

namespace FatcaIdesPhp;

use Monolog\Logger;

// Converter from FatcaDataArray to FatcaDataOecd
class Factory {

  public function array2oecd(FatcaDataArray $fda) {
    $a2o = new Array2OecdBuilder($fda);

    // convert to FATCA_OECD and return FatcaDataOecd
    // much of this is inspired from fatca-ides-php/src/FatcaIdesPhp/FataDataArray
    $oecd=new \FatcaXsdPhp\FATCA_OECD();
    $oecd->version="1.1";
    $oecd->MessageSpec = $a2o->getMessageSpec();

    $oecd->FATCA=new \FatcaXsdPhp\Fatca_Type();
    $oecd->FATCA->ReportingFI = $a2o->getReportingFI();

    $oecd->FATCA->ReportingGroup = new \FatcaXsdPhp\ReportingGroup();

    // gather account reports
    if(array_key_exists("AccountReports", $a2o->fda->data)) {
      $accountReports = array();
      foreach($a2o->fda->data["AccountReports"] as $x) {
        $ar = new \FatcaXsdPhp\CorrectableAccountReport_Type();
        $ar->DocSpec = new \FatcaXsdPhp\DocSpec_Type();
        $ar->DocSpec->DocTypeIndic=$a2o->fda->docType;
        $ar->DocSpec->DocRefId=$a2o->fda->guidManager->get();

        $ar->AccountNumber = $x['Compte'];

        $ar->AccountHolder = $a2o->getAccountHolder($x);

        if(array_key_exists("SubstantialOwner",$x)) {
          if($x["ENT_TYPE"]!="Corporate") throw new \Exception("Cannot have type Individual and substantial owners for: ".$x["Compte"]);

          $substOwns = array();
          foreach($x["SubstantialOwner"] as $so) {
            $subst = new \FatcaXsdPhp\SubstantialOwner_Type();
            $subst->Individual = $a2o->getIndividual($so);
            array_push($substOwns,$subst);
          }
          if(count($substOwns)>0) $ar->SubstantialOwner = $substOwns;
        }

        $ar->AccountBalance = new \oecd\ties\stffatcatypes\v1\MonAmnt_Type();
        $ar->AccountBalance->currCode = $x['cur'];
        $ar->AccountBalance->value = $x['posCur'];

        $payments=$a2o->getPayments($x);
        if(count($payments)>0) $ar->Payment = $payments;
        array_push($accountReports,$ar);
      }
      if(count($accountReports)>0) {
        $oecd->FATCA->ReportingGroup->AccountReport = $accountReports;
      }
    }

    // add pool reports
    if(array_key_exists("PoolReports", $a2o->fda->data)) {
      $poolReports = array();
      foreach($a2o->fda->data["PoolReports"] as $x) {
        $ar = new \FatcaXsdPhp\CorrectablePoolReport_Type();
        $ar->DocSpec = new \FatcaXsdPhp\DocSpec_Type();
        $ar->DocSpec->DocTypeIndic=$a2o->fda->docType;
        $ar->DocSpec->DocRefId=$a2o->fda->guidManager->get();

        $ar->AccountCount = $x['AccountCount'];
        $ar->AccountPoolReportType = new \FatcaXsdPhp\FatcaAcctPoolReportType_EnumType();
        $ar->AccountPoolReportType->value = $x['AccountPoolReportType'];

        $ar->PoolBalance = new \oecd\ties\stffatcatypes\v1\MonAmnt_Type();
        $ar->PoolBalance->currCode = $x['PoolBalance']['cur'];
        $ar->PoolBalance->value = $x['PoolBalance']['posCur'];
        array_push($poolReports,$ar);
      }
      if(count($poolReports)>0) {
        $oecd->FATCA->ReportingGroup->PoolReport = $poolReports;
      }
    }

    return new FatcaDataOecd($oecd);
  }

  // get transmitter that allows to send data to IRS
  public function transmitter(FatcaDataInterface $fdi, string $format,$emailTo,$config,$LOG_LEVEL=Logger::WARNING) {
    $conMan = new ConfigManager($config,$LOG_LEVEL);
    // as of 2016-06-27, the production server still uses ECB
    // as of 2017-03-31, the production server uses CBC
		$am=new AesManager("CBC");
    $rm = new RsaManager($conMan,$am);

    $tmtr=new Transmitter($fdi,$conMan,$rm,$LOG_LEVEL);
    $tmtr->start();
    $tmtr->toXml(); # convert to xml

    if($format!="xml") {
      $exitCond=in_array($format,array("email","emailAndUpload","upload","zip"));
      if(!$tmtr->validateXml("payload")) {# validate
        $msg = 'Payload xml did not pass its xsd validation';
        if($exitCond) { throw new \Exception($msg); } else { print $msg; }
        Utils::libxml_display_errors();
      }

      if(!$tmtr->validateXml("metadata")) {# validate
          $msg = 'Metadata xml did not pass its xsd validation';
          if($exitCond) { throw new \Exception($msg); } else { print $msg; }
          Utils::libxml_display_errors();
      }
    }

    $tmtr->toXmlSigned();
    if(!$tmtr->verifyXmlSigned()) die("Verification of signature failed");

    $tmtr->toCompressed();
    $tmtr->toEncrypted();
    $tmtr->rm->encryptAesKeyFile();
    //	if(!$tmtr->rm->verifyAesKeyFileEncrypted()) die("Verification of aes key encryption failed");
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


  public function receiver($config,$zipFn=null,$credentials=null,$idesServer=null,$LOG_LEVEL=Logger::WARNING) {

    assert(is_null($zipFn) xor is_null($credentials));

    $cm = new ConfigManager($config,$LOG_LEVEL);
		$am=new AesManager();
    $rm = new RsaManager($cm,$am);

    if(is_null($zipFn)) {
      assert(is_array($credentials) && array_key_exists("username",$credentials) && array_key_exists("password",$credentials));
      assert(!is_null($idesServer) && in_array($idesServer,array("live","test")));
      $sftp = SftpWrapper::getSFTP($idesServer);
      $sw = new SftpWrapper($sftp,$LOG_LEVEL);

      $err = $sw->login($credentials["username"],$credentials["password"]);
      if(!!$err) throw new \Exception($err);

      $remote = $sw->listLatest();
      if(array_key_exists("ZipBackupFolder",$cm->config)) {
        $zipFn=$cm->config["ZipBackupFolder"]."/".$remote;
      } else {
        $zipFn = Utils::myTempnam("zip");
        unlink($zipFn);
      }

      if(!file_exists($zipFn)) {
        $sw->get($remote,$zipFn);
      } else {
        $sw->log->debug("Using cached file '".$zipFn."'");
      }

    }

    $rx=new Receiver($cm,$rm);
    $rx->start();
    $rx->fromZip($zipFn);
    $rx->rm->decryptAesKey();
    $rx->fromEncrypted();
    $rx->fromCompressed();
    return $rx;
  }

} //end class
