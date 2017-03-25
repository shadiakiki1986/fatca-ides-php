<?php

namespace FatcaIdesPhp;

// Implementation of FatcaDataInterface that is suitable for institutions that have only a flat array of individuals with reportable accounts
class FatcaDataOecd implements FatcaDataInterface {

	function __construct(\FatcaXsdPhp\FATCA_OECD $oecd) {
    $this->oecd=$oecd;
  }

  function start() {
    ; // do nothing
	}

	public function toHtml() {
    $builder = new FdoHtmlBuilder($this->oecd);
    $dom = new \DOMDocument('1.0', 'utf-8');

    if(!!$this->oecd->FATCA->ReportingGroup->AccountReport) {
      $table = $builder->toHtmlAccountReport($dom);
      $dom->appendChild($table);
    } else {
      $p = $dom->createElement('p', 'No account reports');
      $dom->appendChild($p);
    }

    $br = $dom->createElement('br');
    $dom->appendChild($br);

    // ---------------------------------
    // pool reports
    if(!!$this->oecd->FATCA->ReportingGroup->PoolReport) {
      $table = $builder->toHtmlPoolReport($dom);
      $dom->appendChild($table);
    } else {
      $p = $dom->createElement('p', 'No pool reports');
      $dom->appendChild($p);
    }

    // ---------------------------------
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
    if(!preg_match("/\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}/",$ts3)) {
      throw new \Exception(sprintf("Timestamp format mismatch: '%s'",$ts3));
    }
    return strtotime($ts3);
  }

} // end class
