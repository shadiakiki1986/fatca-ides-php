<?php

namespace FatcaIdesPhp;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Downloader {

	function __construct($dlFolder=null,$LOG_LEVEL=Logger::WARNING,$uo = null) {

    if(is_null($uo)) $uo = new UrlOpener();
    assert($uo instanceof UrlOpener);
    $this->uo = $uo;

    $this->setDlFolder($dlFolder);
    $this->links = array(
      "schema_main"=>array(
        "url"=>"https://www.irs.gov/pub/fatca/FATCAXMLSchemav1.zip"
      ),
      "schema_meta"=>array(
        "url"=>"https://www.irs.gov/pub/fatca/SenderMetadatav1.1.zip",
      ),
      "irsCrt"=> array(
        "url"=>"https://ides-support.com/Downloads/encryption-service_services_irs_gov.crt"
      )
    );
    $this->checkedAvailable=false;
    $this->log = new Logger('Downloader');
    // http://stackoverflow.com/a/25787259/4126114
    $this->log->pushHandler(new StreamHandler('php://stdout', $LOG_LEVEL)); // <<< uses a stream
	}

  function tempdir() {
    $tempFolder = tempnam(sys_get_temp_dir(), 'MyFileName');
    unlink($tempFolder);
    mkdir($tempFolder);
    return $tempFolder;
  }

  function setDlFolder($dlf) {
    if(is_null($dlf)) $dlf=$this->tempdir();
    $this->downloadFolder=$dlf;
  }

	function checkAvailable() {
    array_walk($this->links,function(&$v) {
      $v["cache"]=$this->downloadFolder."/".basename($v["url"]);
      $v["exists"]=file_exists($v["cache"]);
    });
    $this->checkedAvailable=true;
  }

  function download() {
    if(!$this->checkedAvailable) $this->checkAvailable();

    foreach($this->links as $k=>$v) {

      if(!$v["exists"]) {
        // http://stackoverflow.com/a/3938551/4126114
        $this->uo->download($v["cache"],$v["url"]);
        $this->log->debug("Downloading ".$v["url"]." to ".$v["cache"]);
        // for zip files, extract contents
        // http://stackoverflow.com/a/10368236/4126114
        $ext = pathinfo($v["cache"], PATHINFO_EXTENSION);
        if($ext=="zip") {
          $this->log->debug("Unzipping ".$v["cache"]);
          $zip = new \ZipArchive;
          if ($zip->open($v["cache"]) === TRUE) {
            $zip->extractTo($this->downloadFolder);
            $zip->close();
          } else {
            throw new \Exception("Failed to extract zip file ".$v["cache"]);
          }
        }
      }
    }
  }

  function asTransmitterConfig() {
    return array(
      "downloadFolder"=>$this->downloadFolder,
      "FatcaIrsPublic"=>$this->links["irsCrt"]["cache"],
      "FatcaXsd"=>$this->downloadFolder."/FATCA XML Schema v1.1/FatcaXML_v1.1.xsd",
      "MetadataXsd"=>$this->downloadFolder."/FATCA IDES SENDER FILE METADATA XML LIBRARY/FATCA-IDES-SenderFileMetadata-1.1.xsd"
    );
  }

} // end class
