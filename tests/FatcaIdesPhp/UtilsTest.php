<?php

namespace FatcaIdesPhp;

class UtilsTest extends \PHPUnit_Framework_TestCase {

  public function testNewGuid() {
    $ga = array_map(function() { return Utils::newGuid(); },range(1,100));
    $gu = array_unique($ga);
    $this->assertTrue(count($ga)==count($gu));
    $ge=array_filter($ga,function($x) { return strlen($x)<5; });
    $this->assertTrue(count($ge)==0);
  }

  public function testIsZipOk() {
    $z = new \ZipArchive();
    $fnZ2 = Utils::myTempnam('zip');
    $z->open($fnZ2, \ZIPARCHIVE::CREATE);
    $z->addFromString("bla","bli");
    $z->close(); 
    $this->assertTrue(Utils::isZip($fnZ2));
  }

  public function testIsZipFail() {
    $fnZ2 = Utils::myTempnam('zip');
    $this->assertTrue(!Utils::isZip($fnZ2));
    file_put_contents($fnZ2,'bla');
    $this->assertTrue(!Utils::isZip($fnZ2));
  }

}

