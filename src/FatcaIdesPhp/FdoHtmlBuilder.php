<?php

namespace FatcaIdesPhp;

// Implementation of FatcaDataInterface that is suitable for institutions that have only a flat array of individuals with reportable accounts
class FdoHtmlBuilder {

	function __construct(\FatcaXsdPhp\FATCA_OECD $oecd) {
    $this->oecd=$oecd;
  }

  private function toHtmlIndividual($row,$dom,$ind) {
    $row->appendChild($dom->createElement('td',$ind->TIN->value));
    $row->appendChild($dom->createElement('td',$ind->TIN->issuedBy));

    $row->appendChild($dom->createElement('td',$ind->Name->FirstName));
    $row->appendChild($dom->createElement('td',$ind->Name->LastName));

    $row->appendChild($dom->createElement('td',$ind->Address->CountryCode));
    $row->appendChild($dom->createElement('td',$ind->Address->AddressFree));

    return $row;
  }

  private function toHtmlOrganisation($row,$dom,$org) {
    $row->appendChild($dom->createElement('td',$org->TIN->value));
    $row->appendChild($dom->createElement('td',$org->TIN->issuedBy));
    $row->appendChild($dom->createElement('td',$org->Name->value));
    $row->appendChild($dom->createElement('td',"N/A"));

    $row->appendChild($dom->createElement('td',$org->Address->CountryCode));
    $row->appendChild($dom->createElement('td',$org->Address->AddressFree));
    return $row;
  }


  public function toHtmlAccountReport($dom) {
      $table = $dom->createElement('table');

      $border = $dom->createAttribute('border');
      $border->value=1;
      $table->appendChild($border);

      $caption = $dom->createElement('caption', 'Account Reports');
      $table->appendChild($caption);

      $arar = $this->oecd->FATCA->ReportingGroup->AccountReport;
      if(!is_array($arar)) $arar=array($arar);
      foreach($arar as $ar) {
        $row = $dom->createElement('tr');
        $row->appendChild($dom->createElement('td',$ar->AccountNumber));

        if(!!$ar->AccountHolder->Individual) {
          $row->appendChild($dom->createElement('td','{Individual}'));
          $row = $this->toHtmlIndividual(
            $row,
            $dom,
            $ar->AccountHolder->Individual);
        }

        if(!!$ar->AccountHolder->Organisation) {
          $row->appendChild($dom->createElement('td',$ar->AccountHolder->AcctHolderType->value));
          $row = $this->toHtmlOrganisation(
            $row,
            $dom,
            $ar->AccountHolder->Organisation);
        }

        $row->appendChild($dom->createElement('td',$ar->AccountBalance->currCode));
        $row->appendChild($dom->createElement('td',$ar->AccountBalance->value));

        if(!!$ar->Payment) {
          $arpay = $ar->Payment;
          if(!is_array($arpay)) $arpay=array($arpay);
          foreach($arpay as $pay) {
            $row->appendChild($dom->createElement('td',$pay->Type));
            $row->appendChild($dom->createElement('td',$pay->PaymentAmnt->currCode));
            $row->appendChild($dom->createElement('td',$pay->PaymentAmnt->value));
          }
        }// end if ar->payment

        $table->appendChild($row);

        if(!!$ar->SubstantialOwner) {
          foreach($ar->SubstantialOwner as $so) {
            $type = !!$so->Individual ? "Individual" : "Organisation";
            $so = ((array)$so)[$type];
            $row = $dom->createElement('tr');
            $row->appendChild($dom->createElement('td',$ar->AccountNumber));
            $row->appendChild($dom->createElement('td','{Substantial Owner: '.$type.'}'));
            $row = $type=="Individual" ? $this->toHtmlIndividual($row,$dom,$so) : $this->toHtmlOrganisation($row,$dom,$so);
            $table->appendChild($row);
          }
        }

      }

      return $table;
  }


  public function toHtmlPoolReport($dom) {
      $table = $dom->createElement('table');

      $border = $dom->createAttribute('border');
      $border->value=1;
      $table->appendChild($border);

      $caption = $dom->createElement('caption', 'Pool Reports');
      $table->appendChild($caption);

      $row = $dom->createElement('tr');
      $row->appendChild($dom->createElement('th','Report type'));
      $row->appendChild($dom->createElement('th','Number of accounts'));
      $row->appendChild($dom->createElement('th','Balance value'));
      $row->appendChild($dom->createElement('th','Balance currency'));
      $table->appendChild($row);

      $arar = $this->oecd->FATCA->ReportingGroup->PoolReport;
      foreach($arar as $ar) {
        $row = $dom->createElement('tr');
        $row->appendChild($dom->createElement('td',$ar->AccountPoolReportType->value));
        $row->appendChild($dom->createElement('td',$ar->AccountCount));
        $row->appendChild($dom->createElement('td',$ar->PoolBalance->value));
        $row->appendChild($dom->createElement('td',$ar->PoolBalance->currCode));

        $table->appendChild($row);
      }
      return $table;
  }

} // end class
