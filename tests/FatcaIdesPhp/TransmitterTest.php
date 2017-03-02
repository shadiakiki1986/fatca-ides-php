<?php

namespace FatcaIdesPhp;

class TransmitterTest extends \PHPUnit\Framework\TestCase {

  /**
   * @dataProvider testMockedProvider
   */
  public function testMocked($v,$config,$k) {
      $tmtr=Transmitter::shortcut($v,"html","",$config);

      $expected=__DIR__."/data/testMocked_${k}_payload_unsigned.xml";
      # file_put_contents($expected,$tmtr->dataXml);
      $this->assertXmlStringEqualsXmlFile(
        $expected,
        $tmtr->dataXml,
        $expected
      );

      # The below will fail because I do not inject the signing manager
      # signed xml
      # $expected=__DIR__."/data/testMocked_${k}_payload_signed.xml";
      # # file_put_contents($expected,$tmtr->dataXmlSigned);
      # $this->assertXmlStringEqualsXmlFile(
      #   $expected,
      #   $tmtr->dataXmlSigned);

      # zip file
      # $expected=__DIR__."/data/testMocked_$k.zip";
      # # copy($tmtr->tf4,$expected);
      # $this->assertEquals(md5_file($tmtr->tf4),md5_file($expected));

  }

  public function testMockedProvider() {
    $fdat = new FatcaDataArrayTest();
    $fdat->setUp();

    # instead of mocking this simple class, just use it directly and replace its generated IDs
    $guidMan = new \FatcaIdesPhp\GuidManager();
    $guidMan->guidPrepd = range(1,count($guidMan->guidPrepd));

    $ts1 = strtotime("2010-10-05 04:03:02");
    $ts2 = strftime("%Y-%m-%dT%H:%M:%S",$ts1);
    $fda=new FatcaDataArray($fdat->di,false,"",2014,$fdat->conMan,$guidMan);
    $fda->ts=$ts1;

    $fdot = new FatcaDataOecdTest();
    $fdot->setUp();
    $fdot->oecd->MessageSpec->Timestamp=$ts2;

    // no need to pass in GuidManager here because the dummy fixture doesnt use a random DocRefId field
    $fdo=new FatcaDataOecd($fdot->oecd);

    return [
      [$fda,$fdat->conMan->config,'array'],
      [$fdo,$fdat->conMan->config,'oecd'],
    ];

  }

  public function testToEmail() {
    $this->markTestIncomplete("TBD");
  }

}
