<?php

namespace FatcaIdesPhp;

class TransmitterTest extends \PHPUnit_Framework_TestCase {

  public function testToXml() {

    $di = array(
      array("Compte"=>"1234","ENT_FIRSTNAME"=>"Clyde","ENT_LASTNAME"=>"Barrow","ENT_FATCA_ID"=>"123-1234-123","ENT_ADDRESS"=>"Some street somewhere","ResidenceCountry"=>"US","posCur"=>100000000,"cur"=>"USD"),
      array("Compte"=>"5678","ENT_FIRSTNAME"=>"Bonnie","ENT_LASTNAME"=>"Parker","ENT_FATCA_ID"=>"456-1234-123","ENT_ADDRESS"=>"Dallas, Texas","ResidenceCountry"=>"US","posCur"=>100,"cur"=>"LBP")
    );
    $this->assertTrue(count($di)>0);

    $conMan = $this->getMockBuilder('\FatcaIdesPhp\ConfigManager')
                   ->disableOriginalConstructor()
                   ->getMock();
    $conMan->config=array(
      "FatcaKeyPrivate"=>__DIR__."/../../vendor/robrichards/xmlseclibs/tests/privkey.pem",
      "FatcaCrt"=>__DIR__."/../../vendor/robrichards/xmlseclibs/tests/mycert.pem",
      // below public key extracted manually from certificate above using
      // openssl x509 -pubkey -noout -in mycert.pem  > pubkey.pem
      "FatcaKeyPublic"=>__DIR__."/pubkey.pem",
      "ffaid"=>123456789
    );

    $fca=new Transmitter($di,false,"",2014,$conMan);
    $fca->start();
    $fca->toXml(false); # convert to xml 
    $diXml1=$fca->dataXml;

    $fca->toXml(true); # use utf8
    $diXml2=$fca->dataXml;

    $this->assertTrue($diXml1==$diXml2); # else print 'UTF8 changed';

    //file_put_contents("/home/shadi/Development/f1.xml",$diXml1);
    //file_put_contents("/home/shadi/Development/f2.xml",$diXml2);
  }
}
