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

	private $aeskey;
	private $iv;
  private $sizeKey;
  private $sizeIv;

	function __construct($algoS="CBC") {
    // some code to play around with openssl random generator
    //    $x=openssl_random_pseudo_bytes(32);
    //    $y=unpack("H*",$x);
    //    $this->assertTrue(count($y)==1);
    //    $y=array_values($y)[0];
    //    $this->assertTrue(pack("H*",$y)==$x);

    assert(in_array($algoS,array("CBC","ECB")));

    $this->sizeKey = 32;
		$this->aeskey = openssl_random_pseudo_bytes($this->sizeKey);
    $this->algoS=$algoS;

    switch($algoS) {
      case "CBC":
        $this->algoI=MCRYPT_MODE_CBC;
        break;
      case "ECB":
        $this->algoI=MCRYPT_MODE_ECB;
        break;
      default:
        throw new Exception("Unsupported algo '".$algoS."'. Please use CBC or ECB");
    }

    $this->sizeIv = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, $this->algoI);
    assert($this->sizeIv==16);
    $this->iv = mcrypt_create_iv($this->sizeIv, MCRYPT_RAND);
	}

	function encrypt($in) {
		// $this->aeskey = pack('H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");
		$key_size = strlen($this->aeskey);
		if($key_size != $this->sizeKey) throw new \Exception("Invalid key size ".$key_size);

		// add PCKS7 padding
		// http://php.net/manual/en/function.mcrypt-encrypt.php#47973
		$block = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, strtolower($this->algoS));
		$len = strlen($in);
		$padding = $block - ($len % $block);
		$in .= str_repeat(chr($padding),$padding);

		$o = mcrypt_encrypt(
      MCRYPT_RIJNDAEL_128,
      $this->aeskey,
      $in,
      $this->algoI,
      $this->iv);
		return $o;
  }

	function decrypt($in) {
		//http://stackoverflow.com/a/20016774/4126114
		$dectext = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->aeskey, $in, $this->algoI, $this->iv);
		// http://stackoverflow.com/a/7324793/4126114
		$len = strlen($dectext);
		$pad = ord($dectext[$len-1]);
		$dectext= substr($dectext, 0, strlen($dectext) - $pad);

		return $dectext;
	}

  function setAesIv($concatenated) {
    switch($this->algoS) {
      case "CBC":
        // split aeskey entry into aeskey + iv
        // Reference: https://www.irs.gov/businesses/corporations/fatca-ides-technical-faqs#EncryptionE21
        // http://stackoverflow.com/a/1289114/4126114
        $s1 = strlen($concatenated);
        $s2 = $this->sizeIv+$this->sizeKey;
        if($s1!=$s2) throw new \Exception("Invalid aes key + iv concatenation size ".$s1." should be ".$s2);

        $this->aeskey = substr($concatenated,0,$this->sizeKey);
        $this->iv = substr($concatenated,$this->sizeKey,$this->sizeIv);
        break;

      case "ECB":
        // in this case, this is not really concatenated
        $s1 = strlen($concatenated);
        $s2 = $this->sizeKey;
        if($s1!=$s2) throw new \Exception("Invalid aes key size ".$s1." should be ".$s2);
        $this->aeskey = $concatenated;
        break;
    }
  }

  function getAesIv() {
    return $this->aeskey.$this->iv;
  }

} // end class
