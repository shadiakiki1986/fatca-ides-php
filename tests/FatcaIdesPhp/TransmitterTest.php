<?php

namespace FatcaIdesPhp;

class TransmitterTest extends \PHPUnit_Framework_TestCase {

  public function testShortcutArray() {
    $fdat = new FatcaDataArrayTest();
    $fdat->setUp();
    $fda=new FatcaDataArray($fdat->di,false,"",2014,$fdat->conMan);

    $tmtr=Transmitter::shortcut($fda,"html","",$fdat->conMan->config);
    $this->assertTrue(true);
  }

  public function testShortcutOecd() {
    $fdat = new FatcaDataArrayTest();
    $fdat->setUp();

    $fdot = new FatcaDataOecdTest();
    $fdot->setUp();
    $fdo=new FatcaDataOecd($fdot->oecd);

    $tmtr=Transmitter::shortcut($fdo,"html","",$fdat->conMan->config);
    $this->assertTrue(true);
  }

  public function testToEmail() {
    $this->markTestIncomplete("TBD");
  }

}
