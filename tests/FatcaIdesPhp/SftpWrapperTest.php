<?php

namespace FatcaIdesPhp;

class SftpWrapperTest extends \PHPUnit_Framework_TestCase {

    public function setUp() {
      $sftp = $this->getMockBuilder('\phpseclib\Net\SFTP')
                   ->disableOriginalConstructor() 
                   ->getMock();
      $sftp->method('isConnected')
           ->willReturn(true);
      $sftp->method('login')
           ->willReturn(true);
      $sftp->method('isAuthenticated')
           ->willReturn(true);
      $sftp->method('put')
           ->willReturn(true);
      // https://phpunit.de/manual/current/en/test-doubles.html#test-doubles.stubs.examples.StubTest5.php
      $sftp->method('nlist')
           ->will($this->returnCallback(function($x) {
               if($x==".") return array("Outbox");
               if($x=="Outbox") return array("840");
               if($x=="Outbox/840") return array("foo.zip");
               return array();
           }));

      $this->gm=new SftpWrapper($sftp);
    }

    public function testLogin() {
      $this->gm->login("user","pass");
      $this->assertTrue(true);
    }

    public function testGetSFTP() {
      $this->markTestIncomplete("TBD");
    }

    public function testPutOk() {
      $fn="/tmp/foo.zip";
      if(!file_exists($fn)) file_put_contents($fn,"bla");
      $this->assertTrue(file_exists($fn));
      $this->gm->put($fn);
      $this->assertTrue(true);
    }

    public function assertPutFail($fn) {
      try {
        $this->gm->put($fn);
        $this->assertTrue(false);
      } catch(\Exception $e) {
        $this->assertTrue(true);
      }
    }

    public function testPutFail() {
      $fn=Utils::myTempnam("zip");
      $this->assertPutFail($fn);
    }

    public function testPutNotZip() {
      $fn=Utils::myTempnam("txt");
      $this->assertPutFail($fn);
    }

    public function testPutInexistant() {
      $this->assertPutFail("/path/to/file.zip");
    }

}

