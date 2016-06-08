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

}

