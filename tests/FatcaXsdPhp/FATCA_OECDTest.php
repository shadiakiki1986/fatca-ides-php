<?php

namespace FatcaXsdPhp;
use oecd\ties\stffatcatypes\v1\MessageSpec_Type;
use oecd\ties\isofatcatypes\v1\CountryCode_Type;
use oecd\ties\stffatcatypes\v1\Address_Type;
use oecd\ties\stffatcatypes\v1\NameOrganisation_Type;
//use oecd\ties\fatca\v1\ReportingGroup;
use com\mikebevz\xsd2php;


class FATCA_OECDTest extends \PHPUnit_Framework_TestCase {

    public function test1() {
      $root=new FATCA_OECD();
      $root->version="1.1";
      $root->MessageSpec = new MessageSpec_Type();
      $root->MessageSpec->SendingCompanyIN = "123";
      $root->MessageSpec->TransmittingCountry = "LB";
      $root->MessageSpec->ReceivingCountry = "US";
      $root->MessageSpec->MessageType = "FATCA";
      $root->MessageSpec->MessageRefId = "123";
      $root->MessageSpec->ReportingPeriod = "2014-12-31";
      $root->MessageSpec->Timestamp = "2015-02-03T01:01:01";

      $root->FATCA=new Fatca_Type();
      $root->FATCA->ReportingFI = new CorrectableOrganisationParty_Type();
      $root->FATCA->ReportingFI->Name = new NameOrganisation_Type();
      $root->FATCA->ReportingFI->Name->value = "FFA Private Bank";
      $root->FATCA->ReportingFI->Address = new Address_Type();
      $root->FATCA->ReportingFI->Address->CountryCode = new CountryCode_Type();
      $root->FATCA->ReportingFI->Address->CountryCode->value = "LB";

      $root->FATCA->ReportingFI->Address->AddressFree = "Foch street";
      $root->FATCA->ReportingFI->DocSpec = new DocSpec_Type();
      $root->FATCA->ReportingFI->DocSpec->DocTypeIndic="FATCA11";
      $root->FATCA->ReportingFI->DocSpec->DocRefId="123";

      $root->FATCA->ReportingGroup = new ReportingGroup();

      $php2xml = new xsd2php\Php2Xml();
      $xml = $php2xml->getXml($root);
      $expFn = __DIR__."/expected1.xml";
      //file_put_contents($expFn,$xml);
      //var_dump($xml);
      $this->assertEquals($xml,file_get_contents($expFn));
    }

    public function test2() {
      $xml = file_get_contents(__DIR__."/expected1.xml");
      $doc = new \DOMDocument();
      $xmlDom=$doc->loadXML($xml);
      $this->assertTrue($xmlDom, sprintf("Invalid XML: %s",$xml));
      $xsd=__DIR__."/../../cache/FATCA XML Schema v1.1/FatcaXML_v1.1.xsd";
      $this->assertTrue($doc->schemaValidate($xsd));
    }

}

