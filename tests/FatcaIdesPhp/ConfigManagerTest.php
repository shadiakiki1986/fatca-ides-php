<?php

namespace FatcaIdesPhp;

class ConfigManagerTest extends \PHPUnit_Framework_TestCase {

    public function testCheck() {
      $this->markTestIncomplete("TBD");
    }

    public function testRunDownloader() {
      $dm=new Downloader();
      $cm=new ConfigManager(array(),$dm);
      $cm->runDownloader();
      $this->assertEquals(count($cm->config),4);
    }

    public function testPrefixIfNeeded() {
      $dm=new Downloader();
      $cm1 = new ConfigManager(array("ZipBackupFolder"=>"bla"),$dm);
      $cm1->prefixIfNeeded("foo");
      $this->assertEquals($cm1->config["ZipBackupFolder"],"foo/bla");

      $cm2 = new ConfigManager(array("ZipBackupFolder"=>"/bla"),$dm);
      $cm2->prefixIfNeeded("foo");
      $this->assertEquals($cm2->config["ZipBackupFolder"],"/bla");
    }

}

