<?php

namespace FatcaXsdPhp;
use oecd\ties\stffatcatypes\v2\MessageSpec_Type;
use oecd\ties\isofatcatypes\v1\CountryCode_Type;
use oecd\ties\stffatcatypes\v2\Address_Type;
use oecd\ties\stffatcatypes\v2\NameOrganisation_Type;
//use oecd\ties\fatca\v1\ReportingGroup;

class BaseTestCase extends \PHPUnit\Framework\TestCase {

    public function setUp() {
      $root=new FATCA_OECD();
      $root->version="1.1";
      $root->MessageSpec = new MessageSpec_Type();
      $root->MessageSpec->SendingCompanyIN = "ABC123.DEF45.GH.678";
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

      $this->oecd=$root;
    }

}
