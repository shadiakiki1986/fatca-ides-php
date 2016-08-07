<?php

namespace FatcaIdesPhp;

class TransmitterTest extends \PHPUnit_Framework_TestCase {

  public function testMocked() {
    $fdat = new FatcaDataArrayTest();
    $fdat->setUp();

    $fda=new FatcaDataArray($fdat->di,false,"",2014,$fdat->conMan);

    $fdot = new FatcaDataOecdTest();
    $fdot->setUp();
    $fdo=new FatcaDataOecd($fdot->oecd);

    $input = array("array"=>$fda,"oecd"=>$fdo);
    foreach($input as $k=>$v) {

      $tmtr=Transmitter::shortcut($v,"html","",$fdat->conMan->config);
      $expected=__DIR__."/data/testMocked_$k.zip";
      copy($tmtr->tf4,$expected);
      $this->assertEquals(md5_file($tmtr->tf4),md5_file($expected));
    }
  }

  public function testToEmail() {
    $this->markTestIncomplete("TBD");
  }

}
