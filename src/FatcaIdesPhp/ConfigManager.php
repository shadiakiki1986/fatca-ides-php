<?php

namespace FatcaIdesPhp;

class ConfigManager {

  function __construct($config) {
    $this->config=$config;
    $this->checked=false;
  }

  function check() {
    if($this->checked) return;
    $this->checkCompulsory();
    $this->checkExist();
    $this->checked=true;
  }

  function checkCompulsory() {
    $compulsory = array("FatcaKeyPrivate","FatcaXsd","MetadataXsd","ffaid","ffaidReceiver","FatcaCrt");
    foreach($compulsory as $x) {
      if(!array_key_exists($x,$this->config)) {
        throw new \Exception(sprintf("Missing key in config: '%s'",$x));
      }
    }
  }

  function checkExist() {
    $shouldExist = array("FatcaXsd","MetadataXsd","FatcaCrt");
    foreach($shouldExist as $x) {
      if(!file_exists($this->config[$x])) {
        throw new \Exception(sprintf("Missing file defined in config: '%s', '%s'",$x,$this->config[$x]));
      }
    }
  }

}
