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
               if($x=="Inbox/840") return array("f1.zip","f2.zip");
               return array();
           }));

      $this->gm=new SftpWrapper($sftp);

      $z = new \ZipArchive();
      $this->fnZ2 = Utils::myTempnam('zip');
      $z->open($this->fnZ2, \ZIPARCHIVE::CREATE);
      $z->addFromString("bla","bla");
      $z->close(); 
    }

    public function testLogin() {
      $this->assertTrue(
        !$this->gm->login("user","pass"));
    }

    public function testPutNotRealZip() {
      $fn=Utils::myTempnam("zip");
      file_put_contents($fn,"bla");
      $this->assertTrue(!!$this->gm->put($fn));
    }

    public function testPutOk() {
      $err = $this->gm->put($this->fnZ2,"foo.zip");
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

    public function testListLatestOk() {
      $zf = $this->gm->listLatest();
      $this->assertTrue(!!$zf);
    }

    public function testGetOk() {
      $local=$this->fnZ2;
      $this->gm->get("f1.zip",$local);
      $this->assertTrue(file_exists($local),"sftp download file inexistant");
      $this->assertTrue(Utils::isZip($local),"sftp download file not zip");
    }

}

