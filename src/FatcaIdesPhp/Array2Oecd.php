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
    $ms->MessageRefId = $this->fda->guidManager->guidPrepd[0]; // specifically indexing guidPrepd instead of using the get function so as to get the same ID if I run this function twice

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
    $rfi->DocSpec->DocRefId=$this->fda->guidManager->guidPrepd[2];
    // corrDocRefId ...
    return $rfi;
  }

  function getAccountHolder($x) {
      $ah = new \FatcaXsdPhp\AccountHolder_Type();
      $ah->Individual = new \oecd\ties\stffatcatypes\v1\PersonParty_Type();
      $ah->Individual->TIN = $x['ENT_FATCA_ID'];
      $ah->Individual->Name = new \oecd\ties\stffatcatypes\v1\NamePerson_Type();
      $ah->Individual->Name->FirstName = $x['ENT_FIRSTNAME'];
      $ah->Individual->Name->LastName = $x['ENT_LASTNAME'];
      $ah->Individual->Address = new \oecd\ties\stffatcatypes\v1\Address_Type();
      $ah->Individual->Address->CountryCode = $x['ResidenceCountry'];
      $ah->Individual->Address->AddressFree = $x['ENT_ADDRESS'];
      return $ah;
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
      $ar->DocSpec->DocRefId=$this->fda->guidManager->guidPrepd[4];

      $ar->AccountNumber = $x['Compte'];

      $ar->AccountHolder = $this->getAccountHolder($x);

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
