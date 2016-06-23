<?php

namespace FatcaIdesPhp;

class ConfigManagerTest extends \PHPUnit_Framework_TestCase {

    public function setUp() {
      $uo = $this->getMockBuilder('\FatcaIdesPhp\UrlOpener')
                   ->disableOriginalConstructor()
                   ->getMock();
      $uo->method('download')
         ->will($this->returnCallback(function($cache,$url) {
            $z = new \ZipArchive();
            $z->open($cache, \ZIPARCHIVE::CREATE);
            $z->addFromString("bla","bla");
            $z->close();
         }));
      $this->dm=new Downloader(null,\Monolog\Logger::WARNING,$uo);
    }


    public function testCheck() {
      $this->markTestIncomplete("TBD");
    }

    public function testRunDownloader() {
      $cm=new ConfigManager(array(),$this->dm);
      $cm->runDownloader();
      $this->assertEquals(count($cm->config),4);
    }

    public function testPrefixIfNeeded() {
      $dm=new Downloader();
      $cm1 = new ConfigManager(array("ZipBackupFolder"=>"bla"),$this->dm);
      $cm1->prefixIfNeeded("foo");
      $this->assertEquals($cm1->config["ZipBackupFolder"],"foo/bla");

      $cm2 = new ConfigManager(array("ZipBackupFolder"=>"/bla"),$this->dm);
      $cm2->prefixIfNeeded("foo");
      $this->assertEquals($cm2->config["ZipBackupFolder"],"/bla");
    }

}

