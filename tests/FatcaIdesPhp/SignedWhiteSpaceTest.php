<?php

namespace FatcaIdesPhp;

class SignedWhiteSpaceTest extends PHPUnit_Framework_TestCase {

  public function test() {

    // if installation instructions were not followed by copying the file getFatcaData-SAMPLE to getFatcaData, then just use the sample file
    if(!file_exists(ROOT_IDES_DATA.'/lib/getFatcaData.php')) {
      require_once ROOT_IDES_DATA.'/lib/getFatcaData-SAMPLE.php'; // use sample file
    } else {
      require_once ROOT_IDES_DATA.'/lib/getFatcaData.php';
    }

    // retrieval from mf db table
    $di=getFatcaData(2014);
    $this->assertTrue(count($di)>0);

    $fca=new Transmitter($di,false,"",2014);
    $fca->toXml(); # convert to xml 

    if(!$fca->validateXml("payload")) {# validate
        print 'Payload xml did not pass its xsd validation';
        libxml_display_errors();
    }               
    $this->assertTrue($fca->validateXml("payload")); 

    if(!$fca->validateXml("metadata")) {# validate
        print 'Metadata xml did not pass its xsd validation';
        libxml_display_errors();
    }
    $this->assertTrue($fca->validateXml("metadata")); 

    $diXml=$fca->toXmlSigned();
    $this->assertTrue($fca->verifyXmlSigned()); # else print 'preservewhitespace=true => signature not verified'.PHP_EOL;
  }
}
