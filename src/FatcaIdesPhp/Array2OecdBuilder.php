<?php

namespace FatcaIdesPhp;

// Converter from FatcaDataArray to FatcaDataOecd
// Builder design pattern
class Array2OecdBuilder {

  public function __construct(FatcaDataArray $fda) {
    $this->fda=$fda;
  }

  public function getMessageSpec() {
    $ms = new \oecd\ties\stffatcatypes\v2\MessageSpec_Type();
    $ms->SendingCompanyIN = $this->fda->conMan->config["ffaid"];
    $ms->TransmittingCountry = "LB";
    $ms->ReceivingCountry = "US";
    $ms->MessageType = "FATCA";
    $ms->MessageRefId = $this->fda->guidManager->get();

    $ms->ReportingPeriod = sprintf("%s-12-31",$this->fda->taxYear);
    $ms->Timestamp = $this->fda->ts3;
    return $ms;
  }

  public function getReportingFI() {
    $rfi = new \FatcaXsdPhp\CorrectableReportOrganisation_Type();
    $rfi->Name = new \oecd\ties\stffatcatypes\v1\NameOrganisation_Type();
    $rfi->Name->value = "FFA Private Bank";
    $rfi->Address = new \oecd\ties\stffatcatypes\v2\Address_Type();
    $rfi->Address->CountryCode = new \oecd\ties\isofatcatypes\v1\CountryCode_Type();
    $rfi->Address->CountryCode->value = "LB";
    $rfi->Address->AddressFree = "Foch street";

    $rfi->TIN = $this->getTin(
      $this->fda->conMan->config["ffaid"],
      "US");

    $rfi->DocSpec = new \FatcaXsdPhp\DocSpec_Type();
    $rfi->DocSpec->DocTypeIndic=$this->fda->docType;
    $rfi->DocSpec->DocRefId=$this->fda->guidManager->get();

    $rfi->FilerCategory = new \FatcaXsdPhp\FatcaFilerCategory_EnumType();
    $rfi->FilerCategory->value = "FATCA604";

    // corrDocRefId ...

    return $rfi;
  }

  public function getAccountHolder(array $x) {
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

  public function getTin(string $tin, string $issuer) {
    $TIN = new \oecd\ties\stffatcatypes\v2\TIN_Type();
    $TIN->value=$tin;
    $TIN->issuedBy=$issuer;
    return $TIN;
  }

  public function getTinWrapper($x) {
    return $this->getTin(
      Utils::cleanTin($x['ENT_FATCA_ID']),
      $x["ENT_FATCA_ID_ISSUER"]
    );
  }

  public function getIndividual(array $x) {
    $ind = new \oecd\ties\stffatcatypes\v2\PersonParty_Type();
    $ind->TIN = $this->getTinWrapper($x);

    $ind->Name = new \oecd\ties\stffatcatypes\v2\NamePerson_Type();
    $ind->Name->FirstName = $x['ENT_FIRSTNAME'];
    $ind->Name->LastName = $x['ENT_LASTNAME'];
    $ind->Address = new \oecd\ties\stffatcatypes\v2\Address_Type();
    $ind->Address->CountryCode = $x['ResidenceCountry'];
    $ind->Address->AddressFree = Utils::cleanAddress($x['ENT_ADDRESS']);
    return $ind;
  }

  public function getOrganisation(array $x) {
    $org = new \oecd\ties\stffatcatypes\v2\OrganisationParty_Type();
    $org->Name = new \oecd\ties\stffatcatypes\v1\NameOrganisation_Type();
    $org->Name->value = $x['ENT_FIRSTNAME'];
    $org->Address = new \oecd\ties\stffatcatypes\v2\Address_Type();
    $org->Address->CountryCode = $x['ResidenceCountry'];
    $org->Address->AddressFree = Utils::cleanAddress($x['ENT_ADDRESS']);
    $org->TIN = $this->getTinWrapper($x);

    return $org;
  }

  public function getPayment_Type(string $type, string $currCode, float $value) {
    $pay = new \FatcaXsdPhp\Payment_Type();
    $pay->Type = $type;
    $pay->PaymentAmnt = new \oecd\ties\stffatcatypes\v1\MonAmnt_Type();
    $pay->PaymentAmnt->currCode = $currCode;
    $pay->PaymentAmnt->value = $value;
    return $pay;
  }

  public function getPayments(array $x) {
      $payments=array();
      if(array_key_exists('dvdCur',$x)) {
        $pay = $this->getPayment_Type(
          'FATCA501',
          $x['cur'],
          floor($x['dvdCur']));
        array_push($payments,$pay);
      }

      if(array_key_exists('intCur',$x)) {
        $pay = $this->getPayment_Type(
          'FATCA502',
          $x['cur'],
          floor($x['intCur']));
        array_push($payments,$pay);
      }
      if(array_key_exists('grossProceed',$x)) {
        $pay = $this->getPayment_Type(
          'FATCA503',
          $x['cur'],
          floor($x['grossProceed']));
        array_push($payments,$pay);
      }
  

      return $payments;
  }

} //end class
