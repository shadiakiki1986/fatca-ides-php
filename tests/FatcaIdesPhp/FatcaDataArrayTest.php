<?php

namespace FatcaIdesPhp;

class FatcaDataArrayTest extends \PHPUnit\Framework\TestCase {

  public function setUp() {
    $this->di= \yaml_parse_file(__DIR__.'/fdatIndividual.yml');

    $this->conMan = $this->getMockBuilder('\FatcaIdesPhp\ConfigManager')
                   ->disableOriginalConstructor()
                   ->getMock();
    $this->conMan->config=array(
      "FatcaKeyPrivate"=>__DIR__."/../../vendor/robrichards/xmlseclibs/tests/privkey.pem",
      "FatcaCrt"=>__DIR__."/../../vendor/robrichards/xmlseclibs/tests/mycert.pem",
      // below public key extracted manually from certificate above using
      // openssl x509 -pubkey -noout -in mycert.pem  > pubkey.pem
      "FatcaKeyPublic"=>__DIR__."/pubkey.pem",
      // FATCA GIIN
      "ffaid"=>"A1BBCD.00000.XY.123",
      "ffaidReceiver"=>"000000.00000.TA.840"
    );
  }

  public function testToXml() {

    $fda=new FatcaDataArray($this->di,false,"",2014,$this->conMan);
    $diXml1=$fda->toXml(false); # convert to xml 
    $fda->guidManager->guidCount=0;
    $diXml2=$fda->toXml(true); # use utf8

    $this->assertTrue($diXml1==$diXml2); # else print 'UTF8 changed';

    //file_put_contents("/home/shadi/Development/f1.xml",$diXml1);
    //file_put_contents("/home/shadi/Development/f2.xml",$diXml2);
  }

  public function testToHtml() {
    $fda=new FatcaDataArray($this->di,false,"",2014,$this->conMan);
    $html=$fda->toHtml();
    $this->assertTrue(true);
  }

  public function testOrganisation() {
    $di=\yaml_parse_file(__DIR__.'/fdatOrganisation.yml');
    $fda=new FatcaDataArray($di,false,"",2014,$this->conMan);
    try {
      $html=$fda->toHtml();
      $this->assertTrue(false);
    } catch(\Exception $e) {
      $this->assertTrue(true);
    }
  }

}
