<?php

namespace FatcaIdesPhp;

// Implementation of FatcaDataInterface that is suitable for submitting an XML file directly for correcting data
// https://www.irs.gov/pub/fatca/pub5124userguidev20draft.pdf
// http://php.net/manual/en/book.simplexml.php
class FatcaDataXml implements FatcaDataInterface {

	public function __construct(\SimpleXMLElement $oecd) {
    $oecd->registerXPathNamespace("ns0", "urn:oecd:ties:fatca:v2");
    $oecd->registerXPathNamespace("ns1", "urn:oecd:ties:stffatcatypes:v2");
    $this->oecd=$oecd;
  }

  public function start() {
    ; // do nothing
	}

	public function toHtml() {
    return $this->oecd->asXML();
	}

	public function toXml($utf8=false) {
      return $this->oecd->asXML();
	}

  private function oecdXpath(string $path) {
    $element = $this->oecd->xpath($path);
    if(!$element) {
      throw new \Exception("Failed to find xml path: ".$path);
    }
    if(count($element)>1) {
      throw new \Exception("Found ".count($element)." elements for path: ".$path);
    }
    if(count($element)==0) {
      throw new \Exception("Found no elements for path: ".$path);
    }
    return $element[0];
  }

  public function getIsTest() {
    $re = "/FATCA1[1-4]/";
    $docTypeIndic = $this->oecdXpath("//ns0:FATCA_OECD/ns0:FATCA/ns0:ReportingFI/ns0:DocSpec/ns0:DocTypeIndic");
    $docTypeIndic = (string) $docTypeIndic;
    return preg_match( $re, $docTypeIndic)==1;
  }

  public function getGiinSender() {
    $company = $this->oecdXpath("//ns0:FATCA_OECD/ns0:MessageSpec/ns1:SendingCompanyIN");
    return $company;
  }

  public function getTaxYear() {
    $period = $this->oecdXpath("//ns0:FATCA_OECD/ns0:MessageSpec/ns1:ReportingPeriod");
    return substr($period,0,4);
  }

  public function getTsBase() {
    $ts3 = $this->oecdXpath("//ns0:FATCA_OECD/ns0:MessageSpec/ns1:Timestamp");
    if(!preg_match("/\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}/",$ts3)) {
      throw new \Exception(sprintf("Timestamp format mismatch: '%s'",$ts3));
    }
    return strtotime($ts3);
  }

} // end class
