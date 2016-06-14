<?php

namespace FatcaIdesPhp;

class FatcaDataOecdTest extends \PHPUnit_Framework_TestCase {

  function setUp() {
    $fot = new \FatcaXsdPhp\FATCA_OECDTest();
    $fot->setUp();
    $this->oecd = $fot->oecd;

    $fdat = new FatcaDataArrayTest();
    $fdat->setUp();
    $this->conMan = $fdat->conMan;
  }

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

  function getYmlFdo($ymlfn) {
    $di=yaml_parse_file($ymlfn);
    $fda=new FatcaDataArray($di,false,"",2014,$this->conMan);
    $fda->start();
    $a2o = new Array2Oecd($fda);
    $a2o->convert();
    return $a2o->fdo;
  }

  public function testToHtmlIndividual() {
    $fdo=$this->getYmlFdo(__DIR__.'/fdatIndividual.yml');
    $fdo->start();
    $html=$fdo->toHtml();
    $this->assertTrue(!!$html);
  }

  public function testToHtmlOrganisation() {
    $fdo=$this->getYmlFdo(__DIR__.'/fdatOrganisation.yml');
    $fdo->start();
    $html=$fdo->toHtml();
    $this->assertTrue(!!$html);
  }


}
