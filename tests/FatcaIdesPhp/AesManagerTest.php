<?php

namespace FatcaIdesPhp;

class AesManagerTest extends \PHPUnit_Framework_TestCase {

    public function testNoDuplicates() {
	$gm=new AesManager();
	$x="something";
	$y=$gm->decrypt($gm->encrypt($x));
	$this->assertTrue($y==$x);
    }

}

