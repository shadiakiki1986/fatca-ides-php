<?php

namespace FatcaIdesPhp;

// Implementation of FatcaDataInterface that is suitable for institutions that have only a flat array of individuals with reportable accounts
class FatcaDataOecd implements FatcaDataInterface {

	function __construct($oecd) {
	// oecd: php object of type FatcaXsdPhp\FATCA_OECD

    assert($oecd instanceOf \FatcaXsdPhp\FATCA_OECD,"Test that ".get_class($oecd)." is of class \FatcaXsdPhp\FATCA_OECD: ");
    $this->oecd=$oecd;
  }

  function start() {
    ; // do nothing
	}

	function toHtml() {
    $dom = new \DOMDocument('1.0', 'utf-8');
    $table = $dom->createElement('table');

    $border = $dom->createAttribute('border');
    $border->value=1;
    $table->appendChild($border);

    if(!!$this->oecd->FATCA->ReportingGroup->AccountReport &&
         is_array($this->oecd->FATCA->ReportingGroup->AccountReport)
      ) {
      foreach($this->oecd->FATCA->ReportingGroup->AccountReport as $ar) {
        $row = $dom->createElement('tr');
        $row->appendChild($dom->createElement('td',$ar->AccountNumber));
        $row->appendChild($dom->createElement('td',$ar->AccountHolder->Individual->TIN));
        $row->appendChild($dom->createElement('td',$ar->AccountHolder->Individual->Name->FirstName));
        $row->appendChild($dom->createElement('td',$ar->AccountHolder->Individual->Name->LastName));
        $row->appendChild($dom->createElement('td',$ar->AccountHolder->Individual->Address->CountryCode));
        $row->appendChild($dom->createElement('td',$ar->AccountHolder->Individual->Address->AddressFree));
        $table->appendChild($row);
      }
    }

    $dom->appendChild($table);

    // pretty print html
    $dom->preserveWhiteSpace = true;
    $dom->formatOutput = true;//false;
    $html=$dom->saveHTML();
    return $html;
	}

	function toXml($utf8=false) {
      $php2xml = new \com\mikebevz\xsd2php\Php2Xml();
      return $php2xml->getXml($this->oecd);
	}

  function getIsTest() {
    $re = "/FATCA1[1-4]/";
    return preg_match(
      $re,
      $this->oecd->FATCA->ReportingFI->DocSpec->DocTypeIndic);
  }

  function getGiinSender() {
    return $this->oecd->MessageSpec->SendingCompanyIN;
  }

  function getTaxYear() {
    return substr(
      $this->oecd->MessageSpec->ReportingPeriod,
      0,4);
  }

  function getTsBase() {
    $ts3 = $this->oecd->MessageSpec->Timestamp;
    assert(preg_match("/\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}/",$ts3));
    return strtotime($ts3);
  }

} // end class
