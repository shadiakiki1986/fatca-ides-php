<?php

namespace FatcaIdesPhp;

class InvalidCharactersTest extends \PHPUnit_Framework_TestCase {

  public function test() {
    $this->assertTrue(str_replace("#","","bla#bla")=="blabla");
    $this->assertTrue(str_replace(array(",","#"),"","bl,a#bla")=="blabla");
  }
}
