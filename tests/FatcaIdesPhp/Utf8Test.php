<?php

namespace FatcaIdesPhp;

class Utf8Test extends PHPUnit_Framework_TestCase {

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
    $diXml1=$fca->dataXml;

    $fca->toXml(true); # use utf8
    $diXml2=$fca->dataXml;

    $this->assertTrue($diXml1==$diXml2); # else print 'UTF8 changed';

    //file_put_contents("/home/shadi/Development/f1.xml",$diXml1);
    //file_put_contents("/home/shadi/Development/f2.xml",$diXml2);
  }
}
