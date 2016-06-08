<?php

namespace FatcaIdesPhp;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class ConfigManager {

  function __construct($config,$dlm,$LOG_LEVEL=Logger::WARNING) {
    $this->config=$config;
    $this->checked=false;
    $this->msgs=array();
    assert($dlm instanceof Downloader);
    $this->dlm = $dlm;
    $this->shouldExist = array("FatcaXsd","MetadataXsd","FatcaCrt");

    $this->log = new Logger('ConfigManager');
    // http://stackoverflow.com/a/25787259/4126114
    $this->log->pushHandler(new StreamHandler('php://stdout', $LOG_LEVEL)); // <<< uses a stream
  }

  function check() {
    if($this->checked) return;
    $this->log->debug("Checking config");//,$this->config);

    // check if public files not in config, then download
    $needDownload = array_diff(
      $this->shouldExist,
      array_keys($this->config));
    if(count($needDownload)>0) {
      $this->log->debug(
        "config entries before downloader: ".
        implode(",",array_keys($this->config)));
      $this->runDownloader();
      $this->log->debug(
        "config entries after downloader: ".
        implode(",",array_keys($this->config)));
    } else {
      $this->log->debug("No need to download anything");
    }

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
      if(!file_exists($this->config[$x])) {
        $m=sprintf("Missing files defined in config: '%s', '%s'",$x,$this->config[$x]);
        array_push($this->msgs,$m);
      }
    }
  }

  function runDownloader() {
    $this->log->debug("Running the downloader");
    $dlFolder=null;
    if(array_key_exists("downloadFolder",$this->config)) {
      //throw new \Exception("Please define 'downloadFolder' in your config");
      $this->log->debug("Checking that the download folder '".$this->config["downloadFolder"]."' is a directory");
      if(is_dir($this->config["downloadFolder"])) {
        $dlFolder=$this->config["downloadFolder"];
      } else {
        throw new \Exception("It seems that the download folder '".$this->config["downloadFolder"]."' is not a directory");
      }
    }
    $this->dlm->setDlFolder($dlFolder);
    $this->dlm->download();
    $this->config = array_merge(
      $this->config,
      $this->dlm->asTransmitterConfig());
  }

}
