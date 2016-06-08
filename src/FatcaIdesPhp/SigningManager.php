<?php

namespace FatcaIdesPhp;

class SigningManager {

  function __construct($conMan) {
    assert($conMan instanceOf ConfigManager);
    $this->conMan=$conMan;
  }

	function sign($dataIn) {
	// using https://github.com/robrichards/xmlseclibs
    $this->conMan->check();

		// Load the XML to be signed
		$doc = new \DOMDocument();
		$doc->preserveWhiteSpace = true;
		$doc->formatOutput = true;
		$doc->loadXML($dataIn);

    $xmlSecDSig = new \RobRichards\XMLSecLibs\XMLSecurityDSig();
		$xmlSecDSig->setCanonicalMethod(\RobRichards\XMLSecLibs\XMLSecurityDSig::EXC_C14N);// Use the c14n exclusive canonicalization

		$xx=$xmlSecDSig->addObject($doc->documentElement);

		// Sign using SHA-256
		$xmlSecDSig->addReference(
		    $xx,
		    \RobRichards\XMLSecLibs\XMLSecurityDSig::SHA256//,
		    //array('http://www.w3.org/2000/09/xmldsig#enveloped-signature')
		);

    $xmlSecKey = new \RobRichards\XMLSecLibs\XMLSecurityKey(
      \RobRichards\XMLSecLibs\XMLSecurityKey::RSA_SHA256,
      array('type'=>'private'));
		$xmlSecKey->loadKey($this->conMan->config["FatcaKeyPrivate"], TRUE);// Load the private key
		$xmlSecDSig->sign($xmlSecKey);// Sign the XML file
		$xmlSecDSig->add509Cert(file_get_contents($this->conMan->config["FatcaCrt"]));// Add the associated public key to the signature

		$dataOut = $xx->ownerDocument->saveXML();

		// return
		return $dataOut;
	}

	function verify($dataIn) {
		// https://github.com/robrichards/xmlseclibs/blob/00a07354dd443ceb87521e8d78714dcd1e6d2591/tests/xmlsec-verify.phpt
    $this->conMan->check();

              $doc = new \DOMDocument();
              $doc->loadXML($dataIn);

    $xmlSecDSig = new \RobRichards\XMLSecLibs\XMLSecurityDSig();
		$objDSig = $xmlSecDSig->locateSignature($doc);
		if (! $objDSig) {
			throw new \Exception("Cannot locate Signature Node");
		}
		$xmlSecDSig->canonicalizeSignedInfo();
		$objKey = $xmlSecDSig->locateKey();
		if (! $objKey ) {
			throw new \Exception("We have no idea about the key");
		}
		$objKey->loadKey($this->conMan->config["FatcaKeyPublic"], TRUE);

		return($xmlSecDSig->verify($objKey)==1);
	}

} // end class
