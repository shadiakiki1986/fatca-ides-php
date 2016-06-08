<?php

namespace FatcaIdesPhp;

require_once dirname(__FILE__).'/../config.php';
require_once ROOT_IDES_DATA.'/vendor/autoload.php'; #  if this line throw an error, I probably forgot to run composer install
require_once 'checkConfig.php';

class SigningManager {

	function sign($dataIn) {
	// using https://github.com/robrichards/xmlseclibs
    checkConfig();

		// Load the XML to be signed
		$doc = new DOMDocument();
		$doc->preserveWhiteSpace = true;
		$doc->formatOutput = true;
		$doc->loadXML($dataIn);

		$objDSig = new XMLSecurityDSig();// Create a new Security object 
		$objDSig->setCanonicalMethod(XMLSecurityDSig::EXC_C14N);// Use the c14n exclusive canonicalization

		$xx=$objDSig->addObject($doc->documentElement);

		// Sign using SHA-256
		$objDSig->addReference(
		    $xx,
		    XMLSecurityDSig::SHA256//,
		    //array('http://www.w3.org/2000/09/xmldsig#enveloped-signature')
		);

		$objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA256, array('type'=>'private'));// Create a new (private) Security key
		$objKey->loadKey(FatcaKeyPrivate, TRUE);// Load the private key
		$objDSig->sign($objKey);// Sign the XML file
		$objDSig->add509Cert(file_get_contents(FatcaCrt));// Add the associated public key to the signature

		$dataOut = $xx->ownerDocument->saveXML();

		// return
		return $dataOut;
	}

	function verify($dataIn) {
		// https://github.com/robrichards/xmlseclibs/blob/00a07354dd443ceb87521e8d78714dcd1e6d2591/tests/xmlsec-verify.phpt
              $doc = new DOMDocument();
              $doc->loadXML($dataIn);

		$objXMLSecDSig = new XMLSecurityDSig();
		$objDSig = $objXMLSecDSig->locateSignature($doc);
		if (! $objDSig) {
			throw new Exception("Cannot locate Signature Node");
		}
		$objXMLSecDSig->canonicalizeSignedInfo();
		$objKey = $objXMLSecDSig->locateKey();
		if (! $objKey ) {
			throw new Exception("We have no idea about the key");
		}
		$objKey->loadKey(FatcaKeyPublic, TRUE);

		return($objXMLSecDSig->verify($objKey));
	}

} // end class
