<?php

namespace FatcaIdesPhp;

class TransmitterTest extends \PHPUnit_Framework_TestCase {

  public function testShortcutArray() {
    $fdat = new FatcaDataArrayTest();
    $fdat->setUp();
    $fda=new FatcaDataArray($fdat->di,false,"",2014,$fdat->conMan);

    Transmitter::shortcut($fda,"html","",$fdat->conMan->config);
  }

  public function testShortcutOecd() {
    $fdat = new FatcaDataArrayTest();
    $fdat->setUp();

    $fdot = new FatcaDataOecdTest();
    $fdot->setUp();
    $fdo=new FatcaDataOecd($fdot->oecd);

    Transmitter::shortcut($fdo,"html","",$fdat->conMan->config);
  }

  public function testToEmail() {
    $this->markTestIncomplete("TBD");
  }

}
