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

  private function getYmlFdo($ymlfn) {
    $di=\yaml_parse_file($ymlfn);
    $fda=new FatcaDataArray($di,false,"",2014,$this->conMan);
    $factory = new Factory();
    $fdo = $factory->array2oecd($fda);
    return $fdo;
  }

  public function testToHtmlIndividual() {
    $fdo=$this->getYmlFdo(__DIR__.'/fdatIndividual.yml');
    $fdo->start();
    $actual=$fdo->toHtml();
    $expected = __DIR__.'/fdatIndividual.html';
    $this->assertContains("Account Report", $actual);
    $this->assertContains("Pool Reports", $actual);
    $this->assertNotContains("No account reports", $actual);
    $this->assertNotContains("No pool reports", $actual);

    //file_put_contents($expected,$actual);
    $this->assertEquals(file_get_contents($expected), $actual);
  }

  public function testToHtmlOrganisation() {
    $fdo=$this->getYmlFdo(__DIR__.'/fdatOrganisation.yml');
    $fdo->start();
    $html=$fdo->toHtml();
    $this->assertTrue(!!$html);
  }


}
