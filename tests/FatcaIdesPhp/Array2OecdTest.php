<?php

namespace FatcaIdesPhp;

class Array2OecdTest extends \PHPUnit_Framework_TestCase {

  public function test() {
    $fdat = new FatcaDataArrayTest();
    $fdat->setUp();
    $fda=new FatcaDataArray($fdat->di,false,"",2014,$fdat->conMan);
    $fda->start();

    $a2o = new Array2Oecd($fda);
    $a2o->convert();
    $this->assertTrue(true);
  }

}
