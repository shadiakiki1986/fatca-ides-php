<?php

namespace FatcaIdesPhp;

class AesManager {

	var $aeskey;
	var $iv1,$iv2;

	function __construct($key=null) {
		$key = ($key==null?openssl_random_pseudo_bytes(32):$key);
		if(strlen($key)!=32) throw new \Exception("Invalid key length");
		$this->aeskey=$key;

		$td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, '');
		$this->iv1 = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
		mcrypt_module_close($td);

		$size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
		// size will be 16
		$this->iv2 = mcrypt_create_iv($size, MCRYPT_RAND);
	}

	function encrypt($in) { return $this->encrypt2($in); }

	function decrypt($in) { return $this->decrypt2($in); }

	function encrypt1($in) {
		// https://bugs.php.net/bug.php?id=47125
		$td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, '');
		mcrypt_generic_init($td, $this->aeskey, $this->iv1);
		$o = mcrypt_generic($td, $in);
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
		return $o;
	}

	function encrypt2($in) {
		// $this->aeskey = pack('H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");
		$key_size =  strlen($this->aeskey);
		if($key_size!=32) die("Invalid key size ".$key_size);

		// add PCKS7 padding
		// http://php.net/manual/en/function.mcrypt-encrypt.php#47973
		$block = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, 'ecb');
		$len = strlen($in);
		$padding = $block - ($len % $block);
		$in .= str_repeat(chr($padding),$padding);

		$o = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->aeskey, $in, MCRYPT_MODE_ECB, $this->iv2);
		return $o;
	}

	function decrypt1($in) { return $this->decrypt0($in,$this->iv1); }
	function decrypt2($in) { return $this->decrypt0($in,$this->iv2); }

	function decrypt0($in,$iv) {
		//http://stackoverflow.com/a/20016774/4126114
		$dectext = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->aeskey, $in, MCRYPT_MODE_ECB, $iv);
		// http://stackoverflow.com/a/7324793/4126114
		$len = strlen($dectext);
		$pad = ord($dectext[$len-1]);
		$dectext= substr($dectext, 0, strlen($dectext) - $pad);

		return $dectext;
	}

} // end class
