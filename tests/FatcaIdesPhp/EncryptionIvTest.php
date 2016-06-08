<?php

namespace FatcaIdesPhp;

class EncryptionIvTest extends PHPUnit_Framework_TestCase {

  public function test() {
    $size = mcrypt_get_iv_size(MCRYPT_CAST_256, MCRYPT_MODE_CFB);
    $iv = mcrypt_create_iv($size, MCRYPT_RAND);
    $this->assertTrue($size==16);
    $this->assertTrue(strlen($iv)==$size);

    $size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($size, MCRYPT_RAND);
    $this->assertTrue($size==16);
    $this->assertTrue(strlen($iv)==$size);

    $size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($size, MCRYPT_RAND);
    $this->assertTrue($size==32);
    $this->assertTrue(strlen($iv)==$size);
  }

}
