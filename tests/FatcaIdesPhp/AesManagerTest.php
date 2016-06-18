<?php

namespace FatcaIdesPhp;

class AesManagerTest extends \PHPUnit_Framework_TestCase {

    public function testNoDuplicates() {
      $gm=new AesManager();
      $x="something";
      $y=$gm->decrypt($gm->encrypt($x));
      $this->assertTrue($y==$x);
    }

    public function testSetAesIv() {
      $am1=new AesManager();
      $am2=new AesManager();
      $am2->setAesIv($am1->getAesIv());
      $this->assertEquals($am1->getAesIv(),$am2->getAesIv());
    }

}

