<?php

namespace FatcaIdesPhp;

class XmlSecTest extends PHPUnit_Framework_TestCase {

  public function test() {

    // Load the XML to be signed
    $doc = new DOMDocument();
    $doc->loadXML('<bla><something>else</something></bla>');

    $objDSig = new XMLSecurityDSig();// Create a new Security object 
    $objDSig->setCanonicalMethod(XMLSecurityDSig::EXC_C14N);// Use the c14n exclusive canonicalization

    // Sign using SHA-256
    $objDSig->addReference(
        $doc, 
        XMLSecurityDSig::SHA256, 
        array('http://www.w3.org/2000/09/xmldsig#enveloped-signature')
    );

    $objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA256, array('type'=>'private'));// Create a new (private) Security key
    $objKey->loadKey(FatcaKeyPrivate, TRUE);// Load the private key
    $objDSig->sign($objKey);// Sign the XML file

    if(!defined("FatcaCrt")) throw new Exception("'FatcaCrt' not defined");
    if(!file_exists(FatcaCrt)) throw new Exception(sprintf("'FatcaCrt' doesnt exists: '%s'",FatcaCrt));

    $objDSig->add509Cert(file_get_contents(FatcaCrt));// Add the associated public key to the signature
    $objDSig->appendSignature($doc->documentElement);// Append the signature to the XML

    //$doc2 = new DOMDocument();
    //$doc2->loadXML('<bla><something>else</something></bla>');
    //$xx=$objDSig->addObject($doc2->documentElement);

    //$xml = $xx->ownerDocument->saveXML();
    $xml=$doc->saveXML();
    #var_dump($xml);

    //print $doc->saveXML();// Save the signed XML

    // verify
                  //$doc = new DOMDocument();
                 // $doc->loadXML($xml);

                                    $objXMLSecDSig = new XMLSecurityDSig();
            $objDSig = $objXMLSecDSig->locateSignature($doc);
            if (! $objDSig) {
                    throw new Exception("Cannot locate Signature Node");
            }
      $objXMLSecDSig->canonicalizeSignedInfo();
    /*	$objXMLSecDSig->idKeys = array('wsu:Id');
      $objXMLSecDSig->idNS = array('wsu'=>'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd');
      
      $retVal = $objXMLSecDSig->validateReference();
      if (! $retVal) {
        throw new Exception("Reference Validation Failed");
      }
    */
            $objKey = $objXMLSecDSig->locateKey();
            if (! $objKey ) {
                    throw new Exception("We have no idea about the key");
            }
                    $objKey->loadKey(FatcaKeyPublic, TRUE);
    //var_dump($objKey);
            #var_dump($objXMLSecDSig->verify($objKey));

  }
}
