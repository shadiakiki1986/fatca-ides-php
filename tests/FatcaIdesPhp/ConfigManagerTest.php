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

}

