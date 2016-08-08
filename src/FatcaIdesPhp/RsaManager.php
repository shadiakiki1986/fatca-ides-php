<?php

namespace FatcaIdesPhp;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class RsaManager {

	function __construct($conMan,$am) {
    assert($conMan instanceOf ConfigManager);
    $this->conMan = $conMan;
    assert($am instanceOf AesManager);
    $this->am=$am;
  }

	function readIrsPublicKey($returnResource=true) {
    $kk=$this->conMan->config["FatcaIrsPublic"];
	  $fp=fopen($kk,"r");
	  $pub_key_string=fread($fp,8192);
	  fclose($fp);
	  if(!$returnResource) return $pub_key_string;

    $pub_key="";
    $pub_key=openssl_get_publickey($pub_key_string); 
    if(!$pub_key) throw new \Exception("Invalid public key in file: ".$kk);
    return $pub_key;
	}

	function encryptAesKeyFile() {
		$this->aesEncrypted="";
		if(!openssl_public_encrypt (
        $this->am->getAesIv(),
        $this->aesEncrypted,
        $this->readIrsPublicKey() )) {
      throw new \Exception("Did not encrypt aes key");
    }
		if($this->aesEncrypted=="") throw new \Exception("Failed to encrypt AES key");
	}

	function verifyAesKeyFileEncrypted() {
		$pubk=$this->readIrsPublicKey(true);
		$decrypted="";
		if(!openssl_public_decrypt( $this->aesEncrypted , $decrypted , $pubk )) throw new \Exception("Failed to decrypt aes key for verification purposes");
		return($decrypted==$this->am->getAesIv());
	}

	function decryptAesKey() {
//    if($this->from==$this->conMan->config["ffaid"]) {
//      echo("TRYING TO DECRYPT OWN FILE\n");
//      echo("THIS IS ONLY VALID FROM THE UNIT TESTS\n");
//    }
    // assert($this->from==$this->conMan->config["ffaidReceiver"]);

		$aesIvConcatenated="";
    if(!openssl_private_decrypt(
      $this->aesEncrypted ,
      $aesIvConcatenated ,
      $this->readFfaPrivateKey() ))
        throw new \Exception("Could not decrypt aes key from private key");

    if(!$aesIvConcatenated) throw new \Exception("Failed to decrypt AES key");
    $this->am->setAesIv($aesIvConcatenated);
	}

	function readFfaPrivateKey($returnResource=true) {
    $kk = $this->conMan->config["FatcaKeyPrivate"];
	  $fp=fopen($kk,"r");
	  $priv_key_string=fread($fp,8192);
	  fclose($fp);
	  if(!$returnResource) return $priv_key_string;

		$priv_key="";
		$priv_key=openssl_get_privatekey($priv_key_string); 
    if(!$priv_key) throw new \Exception("Invalid private key string in file: ".$kk);
		return $priv_key;
	}


} // end class
