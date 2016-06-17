<?php

namespace FatcaIdesPhp;

// some code playing around with MCRYPT_...
//    $size = mcrypt_get_iv_size(MCRYPT_CAST_256, MCRYPT_MODE_CFB);
//    $iv = mcrypt_create_iv($size, MCRYPT_RAND);
//    $this->assertTrue($size==16);
//    $this->assertTrue(strlen($iv)==$size);
//
//    $size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
//    $iv = mcrypt_create_iv($size, MCRYPT_RAND);
//    $this->assertTrue($size==16);
//    $this->assertTrue(strlen($iv)==$size);
//
//    $size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
//    $iv = mcrypt_create_iv($size, MCRYPT_RAND);
//    $this->assertTrue($size==32);
//    $this->assertTrue(strlen($iv)==$size);

class AesManager {

	var $aeskey;
	var $iv;

	function __construct($key=null,$iv=null) {
    // some code to play around with openssl random generator
    //    $x=openssl_random_pseudo_bytes(32);
    //    $y=unpack("H*",$x);
    //    $this->assertTrue(count($y)==1);
    //    $y=array_values($y)[0];
    //    $this->assertTrue(pack("H*",$y)==$x);

		$key = ($key==null?openssl_random_pseudo_bytes(32):$key);
		if(strlen($key)!=32) throw new \Exception("Invalid key length");
		$this->aeskey=$key;

    if(is_null($iv)) {
  		$size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
  		// size will be 16
      assert($size==16);
  		$iv = mcrypt_create_iv($size, MCRYPT_RAND);
    }
		$this->iv=$iv;
	}

	function encrypt($in) {
		// $this->aeskey = pack('H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");
		$key_size =  strlen($this->aeskey);
		if($key_size!=32) die("Invalid key size ".$key_size);

		// add PCKS7 padding
		// http://php.net/manual/en/function.mcrypt-encrypt.php#47973
		$block = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, 'ecb');
		$len = strlen($in);
		$padding = $block - ($len % $block);
		$in .= str_repeat(chr($padding),$padding);

		$o = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->aeskey, $in, MCRYPT_MODE_CBC, $this->iv);
		return $o;
  }

	function decrypt($in) {
		//http://stackoverflow.com/a/20016774/4126114
		$dectext = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->aeskey, $in, MCRYPT_MODE_CBC, $this->iv);
		// http://stackoverflow.com/a/7324793/4126114
		$len = strlen($dectext);
		$pad = ord($dectext[$len-1]);
		$dectext= substr($dectext, 0, strlen($dectext) - $pad);

		return $dectext;
	}

} // end class
