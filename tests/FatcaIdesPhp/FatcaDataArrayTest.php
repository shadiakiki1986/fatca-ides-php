<?php

namespace FatcaIdesPhp;

class FatcaDataArrayTest extends BaseTestCase {

  public function testIndividual() {
    $fda=new FatcaDataArray($this->di,false,"",2014,$this->conMan);
    $this->assertNotNull($fda->ts3);
  }

  public function testOrganisation() {
    $di=\yaml_parse_file(__DIR__.'/fdatOrganisation.yml');
    $fda=new FatcaDataArray($di,false,"",2014,$this->conMan);
    $this->assertNotNull($fda->ts3);
  }

}
