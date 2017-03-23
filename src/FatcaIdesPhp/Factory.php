<?php

namespace FatcaIdesPhp;

// Converter from FatcaDataArray to FatcaDataOecd
class Factory {

  public function array2oecd(FatcaDataArray $fda) {
    $a2o = new Array2OecdBuilder($fda);

    // convert to FATCA_OECD and return FatcaDataOecd
    // much of this is inspired from fatca-ides-php/src/FatcaIdesPhp/FataDataArray
    $oecd=new \FatcaXsdPhp\FATCA_OECD();
    $oecd->version="1.1";
    $oecd->MessageSpec = $a2o->getMessageSpec();

    $oecd->FATCA=new \FatcaXsdPhp\Fatca_Type();
    $oecd->FATCA->ReportingFI = $a2o->getReportingFI();

    $oecd->FATCA->ReportingGroup = new \FatcaXsdPhp\ReportingGroup();

    // gather account reports
    $accountReports = array();
    foreach($a2o->fda->data as $x) {
      $ar = new \FatcaXsdPhp\CorrectableAccountReport_Type();
      $ar->DocSpec = new \FatcaXsdPhp\DocSpec_Type();
      $ar->DocSpec->DocTypeIndic=$a2o->fda->docType;
      $ar->DocSpec->DocRefId=$a2o->fda->guidManager->get();

      $ar->AccountNumber = $x['Compte'];

      $ar->AccountHolder = $a2o->getAccountHolder($x);

      if(array_key_exists("SubstantialOwner",$x)) {
        if($x["ENT_TYPE"]!="Corporate") throw new \Exception("Cannot have type Individual and substantial owners for: ".$x["Compte"]);

        $substOwns = array();
        foreach($x["SubstantialOwner"] as $so) {
          $subst = new \FatcaXsdPhp\SubstantialOwner_Type();
          $subst->Individual = $a2o->getIndividual($so);
          array_push($substOwns,$subst);
        }
        if(count($substOwns)>0) $ar->SubstantialOwner = $substOwns;
      }


      $ar->AccountBalance = new \oecd\ties\stffatcatypes\v1\MonAmnt_Type();
      $ar->AccountBalance->currCode = $x['cur'];
      $ar->AccountBalance->value = $x['posCur'];

      $payments=$a2o->getPayments($x);
      if(count($payments)>0) $ar->Payment = $payments;
      array_push($accountReports,$ar);
   }

    if(count($accountReports)>0) $oecd->FATCA->ReportingGroup->AccountReport = $accountReports;

    return new FatcaDataOecd($oecd);
  }

} //end class
