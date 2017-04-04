<?php

namespace FatcaIdesPhp;

class FatcaDataXmlTest extends FatcaDataOecdTest {

  public function setUp() {
    $oecd = simplexml_load_file(__DIR__."/data/testMocked_array_payload_unsigned.xml");
    $this->fdo = new FatcaDataXml($oecd);
  }

  public function testMisc() {
    //var_dump($this->fdo->oecd->xpath('//ns0:FATCA_OECD/ns0:FATCA')[0]->asXml());
    $this->assertFalse($this->fdo->getIsTest());
    $this->assertEquals("A1BBCD.00000.XY.123", $this->fdo->getGiinSender());
    $this->assertEquals("2014", $this->fdo->getTaxYear());
    $this->assertEquals(strtotime("2010-10-05T04:03:02"), $this->fdo->getTsBase());
    $this->assertContains("ns0", $this->fdo->toXml());
  }

}
