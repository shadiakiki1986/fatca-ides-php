<?php

namespace FatcaIdesPhp;

class TransmitterTest extends FatcaDataArrayTest {

  public function testShortcut() {
    $fda=new FatcaDataArray($this->di,false,"",2014,$this->conMan);
    Transmitter::shortcut($fda,"html","",$this->conMan->config);
  }

  public function testToEmail() {
    $this->markTestIncomplete("TBD");
  }

}
