<?php

namespace FatcaIdesPhp;

// Converter from FatcaDataArray to FatcaDataOecd
class Array2Oecd {

  function __construct($fda) {
    assert($fda instanceof FatcaDataArray);
    $this->fda=$fda;
  }

  function getMessageSpec() {
    $ms = new \oecd\ties\stffatcatypes\v1\MessageSpec_Type();
    $ms->SendingCompanyIN = $this->fda->conMan->config["ffaid"];
    $ms->TransmittingCountry = "LB";
    $ms->ReceivingCountry = "US";
    $ms->MessageType = "FATCA";
    $ms->MessageRefId = $this->fda->guidManager->get();

    $ms->ReportingPeriod = sprintf("%s-12-31",$this->fda->taxYear);
    $ms->Timestamp = $this->fda->ts3;
    return $ms;
  }

  function getReportingFI() {
    $rfi = new \FatcaXsdPhp\CorrectableOrganisationParty_Type();
    $rfi->Name = new \oecd\ties\stffatcatypes\v1\NameOrganisation_Type();
    $rfi->Name->value = "FFA Private Bank";
    $rfi->Address = new \oecd\ties\stffatcatypes\v1\Address_Type();
    $rfi->Address->CountryCode = new \oecd\ties\isofatcatypes\v1\CountryCode_Type();
    $rfi->Address->CountryCode->value = "LB";

    $rfi->Address->AddressFree = "Foch street";
    $rfi->DocSpec = new \FatcaXsdPhp\DocSpec_Type();
    $rfi->DocSpec->DocTypeIndic=$this->fda->docType;
    $rfi->DocSpec->DocRefId=$this->fda->guidManager->get();
    // corrDocRefId ...
    return $rfi;
  }

  function getAccountHolder($x) {
      $ah = new \FatcaXsdPhp\AccountHolder_Type();
      switch($x["ENT_TYPE"]) {
        case "Individual":
          $ah->Individual = $this->getIndividual($x);
          break;
        case "Corporate":
          $ah->AcctHolderType = new \FatcaXsdPhp\FatcaAcctHolderType_EnumType();
          $ah->AcctHolderType->value = "FATCA102";
          $ah->Organisation = $this->getOrganisation($x);
          break;
        default:
          throw new \Exception("Unsupported ENT_TYPE '".$x["ENT_TYPE"]."'");
      }
      return $ah;
  }

  function getIndividual($x) {
    $ind = new \oecd\ties\stffatcatypes\v1\PersonParty_Type();
    $ind->TIN = Utils::cleanTin($x['ENT_FATCA_ID']);
    $ind->Name = new \oecd\ties\stffatcatypes\v1\NamePerson_Type();
    $ind->Name->FirstName = $x['ENT_FIRSTNAME'];
    $ind->Name->LastName = $x['ENT_LASTNAME'];
    $ind->Address = new \oecd\ties\stffatcatypes\v1\Address_Type();
    $ind->Address->CountryCode = $x['ResidenceCountry'];
    $ind->Address->AddressFree = Utils::cleanAddress($x['ENT_ADDRESS']);
    return $ind;
  }

  function getOrganisation($x) {
    $org = new \oecd\ties\stffatcatypes\v1\OrganisationParty_Type();
    $org->Name = new \oecd\ties\stffatcatypes\v1\NameOrganisation_Type();
    $org->Name->value = $x['ENT_FIRSTNAME'];
    $org->Address = new \oecd\ties\stffatcatypes\v1\Address_Type();
    $org->Address->CountryCode = $x['ResidenceCountry'];
    $org->Address->AddressFree = Utils::cleanAddress($x['ENT_ADDRESS']);
    return $org;
  }

  function getPayments($x) {
      $payments=array();
      if(array_key_exists('dvdCur',$x)) {
        $pay = new \FatcaXsdPhp\Payment_Type();
        $pay->Type = "FATCA501";
        $pay->PaymentAmnt = new \oecd\ties\stffatcatypes\v1\MonAmnt_Type();
        $pay->PaymentAmnt->currCode = $x['cur'];
        $pay->PaymentAmnt->value = floor($x['dvdCur']);
        array_push($payments,$pay);
      }

      if(array_key_exists('intCur',$x)) {
        $pay = new \FatcaXsdPhp\Payment_Type();
        $pay->Type = "FATCA502";
        $pay->PaymentAmnt = new \FatcaXsdPhp\PayentAmnt_Type();
        $pay->PaymentAmnt->currCode = $x['cur'];
        $pay->PaymentAmnt->value = floor($x['intCur']);
        array_push($payments,$pay);
      }
      return $payments;
  }

  function convert() {
    // convert to FATCA_OECD and return FatcaDataOecd
    // much of this is inspired from fatca-ides-php/src/FatcaIdesPhp/FataDataArray
    $this->oecd=new \FatcaXsdPhp\FATCA_OECD();
    $this->oecd->version="1.1";
    $this->oecd->MessageSpec = $this->getMessageSpec();

    $this->oecd->FATCA=new \FatcaXsdPhp\Fatca_Type();
    $this->oecd->FATCA->ReportingFI = $this->getReportingFI();

    $this->oecd->FATCA->ReportingGroup = new \FatcaXsdPhp\ReportingGroup();

    // gather account reports
    $accountReports = array();
    foreach($this->fda->data as $x) {
      $ar = new \FatcaXsdPhp\CorrectableAccountReport_Type();
      $ar->DocSpec = new \FatcaXsdPhp\DocSpec_Type();
      $ar->DocSpec->DocTypeIndic=$this->fda->docType;
      $ar->DocSpec->DocRefId=$this->fda->guidManager->get();

      $ar->AccountNumber = $x['Compte'];

      $ar->AccountHolder = $this->getAccountHolder($x);

      if(array_key_exists("SubstantialOwner",$x)) {
        if($x["ENT_TYPE"]!="Corporate") throw new Exception("Cannot have type Individual and substantial owners for: ".$x["Compte"]);

        $substOwns = array();
        foreach($x["SubstantialOwner"] as $so) {
          array_push($substOwns,$this->getIndividual($so));
        }
        if(count($substOwns)>0) $ar->SubstantialOwner = $substOwns;
      }


      $ar->AccountBalance = new \oecd\ties\stffatcatypes\v1\MonAmnt_Type();
      $ar->AccountBalance->currCode = $x['cur'];
      $ar->AccountBalance->value = $x['posCur'];

      $payments=$this->getPayments($x);
      if(count($payments)>0) $ar->Payment = $payments;
      array_push($accountReports,$ar);
   }

    if(count($accountReports)>0) $this->oecd->FATCA->ReportingGroup->AccountReport = $accountReports;

    $this->fdo = new FatcaDataOecd($this->oecd);
  }

} //end class
