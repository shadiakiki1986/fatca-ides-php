<?php

namespace FatcaIdesPhp;

class Sha256Test extends PHPUnit_Framework_TestCase {

  public function test() {
    $this->assertTrue(base64_encode(hash("sha256", "blablabla",true))=="SS8/ONa108qFlRTiUOJbplk1vN2fT0DBJLdz/lNv7n0=");
    $this->assertTrue(base64_encode(hash("sha256", "blablabla",false))=="NDkyZjNmMzhkNmI1ZDNjYTg1OTUxNGUyNTBlMjViYTY1OTM1YmNkZDlmNGY0MGMxMjRiNzczZmU1MzZmZWU3ZA==");
  }
}
