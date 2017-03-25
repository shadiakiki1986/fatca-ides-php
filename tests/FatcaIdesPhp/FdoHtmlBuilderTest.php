<?php

namespace FatcaIdesPhp;

class FdoHtmlBuilderTest extends BaseTestCase {

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
