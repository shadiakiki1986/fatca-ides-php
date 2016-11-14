<?php

namespace FatcaIdesPhp;

class DownloaderTest extends \PHPUnit_Framework_TestCase {

    public function setUp() {
      $uo = $this->getMockBuilder('\FatcaIdesPhp\UrlOpener')
                   ->disableOriginalConstructor()
                   ->getMock();
      $uo->method('download')
         ->will($this->returnCallback(function($cache,$url) {
           switch($url) {
             case "https://www.irs.gov/pub/fatca/FATCAXMLSchemav1.zip":
                $z = new \ZipArchive();
                $z->open($cache, \ZIPARCHIVE::CREATE);
                $z->addFromString("FATCA XML Schema v1.1/FatcaXML_v1.1.xsd","bla");
                $z->close();
                break;
             case "https://www.ides-support.com/Downloads/encryption-service_services_irs_gov.crt":
               file_put_contents($cache,"test");
               break;
             case "https://www.irs.gov/pub/fatca/SenderMetadatav1.1.zip":
                $z = new \ZipArchive();
                $z->open($cache, \ZIPARCHIVE::CREATE);
                $z->addFromString("FATCA IDES SENDER FILE METADATA XML LIBRARY/FATCA-IDES-SenderFileMetadata-1.1.xsd","bla");
                $z->close();
               break;
             default:
               throw new Exception("Define url in mock url opener: ".$url);
           }
         }));
      $this->gm=new Downloader(null,\Monolog\Logger::WARNING,$uo);
    }

    public function testTempdir() {
      $this->assertTrue(is_dir($this->gm->downloadFolder));
      $this->assertTrue(!is_file($this->gm->downloadFolder));
    }

    public function testCheckAvailable() {
      $this->gm->checkAvailable();
      $exist=array_column($this->gm->links,"exists");
      $this->assertEquals(count(array_filter($exist,function($x) { return $x; })),0);
    }

    public function testDownload() {
      $this->gm->download();

      $this->gm->checkAvailable();
      $exist=array_column($this->gm->links,"exists");
      $this->assertEquals(count(array_filter($exist,function($x) { return !$x; })),0);
    }

    public function testAsTransmitterConfig() {
      $this->gm->download();
      $o = $this->gm->asTransmitterConfig();
      $exist = array_map(function($x) { return file_exists($x); },$o);
      $missing = array_filter($exist,function($x) { return !$x; });
      $this->assertEquals(count($missing),0);
    }

}

