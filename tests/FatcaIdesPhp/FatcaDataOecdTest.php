<?php

namespace FatcaIdesPhp;

class FatcaDataOecdTest extends BaseTestCase {

  public function setUp() {
    parent::setUp();
    $this->fdo = new FatcaDataOecd($this->oecd);
  }

  public function testToXml() {
    $this->fdo->start();
    $diXml1=$this->fdo->toXml(false); # convert to xml 
    $diXml2=$this->fdo->toXml(true); # use utf8

    $this->assertTrue($diXml1==$diXml2); # else print 'UTF8 changed';

    //file_put_contents("/home/shadi/Development/f1.xml",$diXml1);
    //file_put_contents("/home/shadi/Development/f2.xml",$diXml2);
  }

  public function testToHtmlBuilt() {
    $this->fdo->start();
    $html=$this->fdo->toHtml();
    $this->assertTrue(!!$html);
  }

}
