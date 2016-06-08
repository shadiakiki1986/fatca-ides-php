<?php

namespace FatcaIdesPhp;

class SslRandTest extends \PHPUnit_Framework_TestCase {

  public function test() {
    $x=openssl_random_pseudo_bytes(32);
    $y=unpack("H*",$x);
    $this->assertTrue(count($y)==1);
    $y=array_values($y)[0];
    $this->assertTrue(pack("H*",$y)==$x);
  }
}
