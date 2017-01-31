<?php

namespace FatcaIdesPhp;

class Array2OecdTest extends \PHPUnit_Framework_TestCase {

  function setUp() {
    $fdat = new FatcaDataArrayTest();
    $fdat->setUp();
    $this->conMan = $fdat->conMan;
  }

  function assertSchemaValidate($xml) {
    $xsd=__DIR__."/../../assets/fatcaxml/FatcaXML.xsd";
    $doc = new \DOMDocument();
    $xmlDom=$doc->loadXML($xml);
    $this->assertTrue($doc->schemaValidate($xsd));
  }

  public function testIndividual() {
    $di=\yaml_parse_file(__DIR__.'/fdatIndividual.yml');
    $fda=new FatcaDataArray($di,false,"",2014,$this->conMan);
    $fda->start();

    $a2o = new Array2Oecd($fda);
    $a2o->convert();
    $this->assertSchemaValidate($a2o->fdo->toXml());
  }

  public function testOrganisationOk() {
    $di=\yaml_parse_file(__DIR__.'/fdatOrganisation.yml');
    $fda=new FatcaDataArray($di,false,"",2014,$this->conMan);
    $fda->start();

    $a2o = new Array2Oecd($fda);
    $a2o->convert();
    $this->assertSchemaValidate($a2o->fdo->toXml());
  }

  public function testWrongType() {
    $di=\yaml_parse_file(__DIR__.'/fdatIndividual.yml');
    $di[0]["ENT_TYPE"]="inexistant";
    $fda=new FatcaDataArray($di,false,"",2014,$this->conMan);
    $fda->start();

    $a2o = new Array2Oecd($fda);
    try {
      $a2o->convert();
      $this->assertTrue(false);
    } catch(\Exception $e) {
      $this->assertTrue(true);
    }
  }

}
