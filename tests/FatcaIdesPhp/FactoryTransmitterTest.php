<?php

namespace FatcaIdesPhp;

class FactoryTransmitterTest extends BaseTestCase {

  /**
   * @dataProvider mockedProvider
   */
  public function testMocked($k) {
      # instead of mocking this simple class, just use it directly and replace its generated IDs
      $guidMan = new \FatcaIdesPhp\GuidManager();
      $guidMan->guidPrepd = range(1,count($guidMan->guidPrepd));

      $factory = new Factory();

      $v=null;
      $ts1 = strtotime("2010-10-05 04:03:02");
      switch($k) {
        case "array":
          $fda=new FatcaDataArray($this->di, false, "", 2014, $this->conMan, $guidMan, $ts1);

          $v = $factory->array2oecd($fda);
          break;
        case "oecd":
          $ts2 = strftime("%Y-%m-%dT%H:%M:%S",$ts1);
          $this->oecd->MessageSpec->Timestamp=$ts2;

          // no need to pass in GuidManager here because the dummy fixture doesnt use a random DocRefId field
          $v=new FatcaDataOecd($this->oecd);
          break;
        default:
          throw new \Exception("What is this target?");
      }

      $tmtr=$factory->transmitter($v,"html","",$this->conMan->config);

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

  public function mockedProvider() {
    return [
      ['array'],
      ['oecd'],
    ];

  }

  public function testToEmail() {
    $this->markTestIncomplete("TBD");
  }

}
