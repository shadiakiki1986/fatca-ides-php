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
		$dv=array_values($this->data);
    $headers=array(
      "ENT_TYPE","ENT_LASTNAME","ENT_FIRSTNAME","ENT_FATCA_ID","ENT_ADDRESS",
      "ResidenceCountry","ENT_COD","posCur","Compte","cur","dvdCur","intCur");

    // assert that there no keys more than those defined above
    array_map(function($x) use($headers) {
      $ad=array_diff(array_keys($x),$headers);
      assert(count($ad)==0,"Test no extra headers failed. Found: ".implode(", ",$ad));
    }, $this->data);

	  $th=implode(array_map(function($x) { return "<th>".$x."</th>"; },$headers));
    $body=array();
    foreach($this->data as $y) {
      $row=array();
      foreach($headers as $x1) {
        if(array_key_exists($x1,$y)) {
          $x2=$y[$x1];
          if(!is_array($x2)) {
            array_push($row, "<td>".$x2."</td>");
          } else {
            die("arrays here No longer supported");
          }
        } else {
          array_push($row,"<td>&nbsp;</td>");
        }
      }
      $row=implode($row);
      array_push($body, "<tr>".$row."</tr>");
    }
    $body=implode($body);

		$html2 = sprintf("<table border=1>%s%s</table>\n",$th,$body);

    // pretty print html
    $doc = new \DOMDocument();
    $doc->preserveWhiteSpace = true;
    $doc->formatOutput = true;//false;
    $doc->loadHTML($html2);
    $html3=$doc->saveHTML();
    return $html3;
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
