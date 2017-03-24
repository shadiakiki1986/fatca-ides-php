<?php

namespace FatcaXsdPhp;
use com\mikebevz\xsd2php;

class FATCA_OECDTest extends BaseTestCase {

    public function test1() {
      $php2xml = new xsd2php\Php2Xml();

      $xml = $php2xml->getXml($this->oecd);
      $expFn = __DIR__."/expected1.xml";
      //file_put_contents($expFn,$xml);
      //var_dump($xml);
      $this->assertEquals(file_get_contents($expFn),$xml);
    }

    public function test2() {
      $xml = file_get_contents(__DIR__."/expected1.xml");
      $doc = new \DOMDocument();
      $xmlDom=$doc->loadXML($xml);
      $this->assertTrue($xmlDom, sprintf("Invalid XML: %s",$xml));
      $xsd=__DIR__."/../../assets/fatcaxml/FatcaXML.xsd";
      $this->assertTrue($doc->schemaValidate($xsd));
    }

}

