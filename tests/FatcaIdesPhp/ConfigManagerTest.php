<?php

namespace FatcaIdesPhp;

class ConfigManagerTest extends \PHPUnit_Framework_TestCase {

    public function testCheck() {
      $this->markTestIncomplete("TBD");
    }

    public function testGetPublic() {
      $cm=new ConfigManager(array(),$this->dm);
      $cm->getPublic();
      $this->assertEquals(count($cm->config),4);
    }

    public function testPrefixIfNeeded() {
      $cm1 = new ConfigManager(array("ZipBackupFolder"=>"bla"));
      $cm1->prefixIfNeeded("foo");
      $this->assertEquals($cm1->config["ZipBackupFolder"],"foo/bla");

      $cm2 = new ConfigManager(array("ZipBackupFolder"=>"/bla"));
      $cm2->prefixIfNeeded("foo");
      $this->assertEquals($cm2->config["ZipBackupFolder"],"/bla");
    }

}

