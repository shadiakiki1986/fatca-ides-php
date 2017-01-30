<?php

namespace FatcaIdesPhp;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class ConfigManager {

  function __construct($config,$LOG_LEVEL=Logger::WARNING) {
    $this->config=$config;
    $this->checked=false;
    $this->msgs=array();
    $this->shouldExist = array("FatcaXsd","MetadataXsd","FatcaCrt","ZipBackupFolder");

    $this->log = new Logger('ConfigManager');
    // http://stackoverflow.com/a/25787259/4126114
    $this->log->pushHandler(new StreamHandler('php://stdout', $LOG_LEVEL)); // <<< uses a stream
  }

  function prefixIfNeeded($prefix) {
    // if path strings do not start with "/", then prefix with ROOT_IDES_DATA/
    $keysToPrefix=array("FatcaCrt","FatcaKeyPrivate","FatcaKeyPublic","ZipBackupFolder");
    $keysToPrefix=array_intersect(array_keys($this->config),$keysToPrefix);
    $keysToPrefix=array_filter($keysToPrefix,function($x) {
      return !preg_match("/^\//",$this->config[$x]);
    });
    foreach($keysToPrefix as $ktp) {
      $this->config[$ktp]=$prefix."/".$this->config[$ktp];
    }
  }

  function check() {
    if($this->checked) return;
    $this->log->debug("Checking config");//,$this->config);

    // get names of public files
    $this->getPublic();

    $this->checkCompulsory();
    $this->checkExist();
    if(count($this->msgs)>0) throw new \Exception(implode("\n",$this->msgs));
    $this->checked=true;
  }

  function checkCompulsory() {
    $compulsory = array("FatcaKeyPrivate","FatcaXsd","MetadataXsd","ffaid","ffaidReceiver","FatcaCrt");
    foreach($compulsory as $x) {
      if(!array_key_exists($x,$this->config)) {
        $m=sprintf("Missing keys in config: %s",$x);
        array_push($this->msgs,$m);
      }
    }
  }

  function checkExist() {
    foreach($this->shouldExist as $x) {
      if(array_key_exists($x,$this->config)) {
        if(!file_exists($this->config[$x])) {
          $m=sprintf("Missing files defined in config: '%s', '%s'",$x,$this->config[$x]);
          array_push($this->msgs,$m);
        }
      }
    }
  }

  function getPublic() {
    $dlFolder = __DIR__."/../../assets";
    $atc = array(
      "FatcaIrsPublic"=>$dlFolder."/encryption-service_services_irs_gov.crt",
      "FatcaXsd"=>$dlFolder."/fatcaxml/FatcaXML.xsd",
      "MetadataXsd"=>$dlFolder."/SenderMetadata/FATCA IDES SENDER FILE METADATA XML LIBRARY/FATCA-IDES-SenderFileMetadata-1.1.xsd"
    );
    // drop from atc any already set keys
    $atc = array_diff_key($atc,$this->config);
    // merge
    $this->config = array_merge(
      $this->config,
      $atc);
  }

}
