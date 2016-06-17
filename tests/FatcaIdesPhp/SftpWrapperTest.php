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
      $this->assertTrue(
        !$this->gm->login("user","pass"));
    }

    public function testPutNotRealZip() {
      $fn="/tmp/foo.zip";
      if(!file_exists($fn)) file_put_contents($fn,"bla");
      $this->assertTrue(file_exists($fn));
      $this->assertTrue(!!$this->gm->put($fn));
    }

    public function testPutOk() {
      $fnT = Utils::myTempnam('txt');
      file_put_contents($fnT,"bla");

      $z = new \ZipArchive();
      $fnZ2 = Utils::myTempnam('zip');
      $z->open($fnZ2, \ZIPARCHIVE::CREATE);
      $z->addFile($fnT, "bla.txt");
      $z->close(); 

      $err = $this->gm->put($fnZ2,"foo.zip");
      $this->assertTrue(!$err,"sftp upload failed: ".$err);
    }

    public function assertPutFail($fn) {
      $this->assertTrue(!!$this->gm->put($fn));
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

    public function testGetSFTP() {
      foreach(array("test","live") as $hostType) {
        $sftp = SftpWrapper::getSFTP($hostType);
        $sftp->_connect();
        $this->assertTrue( $sftp->isConnected());
        $sftp->disconnect();
        $this->assertTrue(!$sftp->isConnected());
      }
    }

}

