<?php

namespace FatcaIdesPhp;

class FatcaDataOecdTest extends BaseTestCase {

  public function testToXml() {
    $fdo=new FatcaDataOecd($this->oecd);
    $fdo->start();
    $diXml1=$fdo->toXml(false); # convert to xml 
    $diXml2=$fdo->toXml(true); # use utf8

    $this->assertTrue($diXml1==$diXml2); # else print 'UTF8 changed';

    //file_put_contents("/home/shadi/Development/f1.xml",$diXml1);
    //file_put_contents("/home/shadi/Development/f2.xml",$diXml2);
  }

  public function testToHtmlBuilt() {
    $fdo=new FatcaDataOecd($this->oecd);
    $fdo->start();
    $html=$fdo->toHtml();
    $this->assertTrue(!!$html);
  }

}
