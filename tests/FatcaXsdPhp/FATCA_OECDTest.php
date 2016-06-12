<?php

namespace FatcaXsdPhp;
use oecd\ties\stffatcatypes\v1\MessageSpec_Type;
use com\mikebevz\xsd2php;


class FATCA_OECDTest extends \PHPUnit_Framework_TestCase {

    public function test1() {
      $root=new FATCA_OECD();
      $root->version="1.1";
      $root->MessageSpec = new MessageSpec_Type();
      $root->MessageSpec->SendingCompanyIN = "123";
      $root->FATCA=new Fatca_Type();

      $php2xml = new xsd2php\Php2Xml();
      $xml = $php2xml->getXml($root);
      $expFn = __DIR__."/expected1.xml";
      // file_put_contents($expFn,$xml);

      $this->assertEquals($xml,file_get_contents($expFn));
    }

}

