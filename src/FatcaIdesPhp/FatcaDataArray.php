<?php

namespace FatcaIdesPhp;

// Implementation of FatcaDataInterface that is suitable for institutions that have only a flat array of individuals with reportable accounts
class FatcaDataArray implements FatcaDataInterface {

	var $data; // php data with fatca information
	var $ts;
	var $ts3;
	var $docType;
	var $corrDocRefId;
	var $taxYear;
	var $guidManager;

  /*
	 * dd: 2d array with fatca-relevant fields
	 * isTest: true|false whether the data is test data. This will only help set the DocTypeIndic field in the XML file
	 * corrDocRefId: false|message ID. If this is a correction of a previous message, pass the message ID in subject, otherwise just pass false
   * guidMan: instance of GuidManager
   */

	function __construct($dd,$isTest,$corrDocRefId,$taxYear,$conMan,$guidMan=null) {

    assert($conMan instanceOf ConfigManager);
    $this->conMan=$conMan;

		$this->data=$dd;
		$this->corrDocRefId=$corrDocRefId;
		$this->isTest=$isTest;
		$this->taxYear=$taxYear;

    date_default_timezone_set('UTC');
    $this->ts=time();

    if(is_null($guidMan)) {
      // prepare guids to use
      $this->guidManager=new GuidManager(
        $this->conMan->config["ffaid"].".");
    } else {
      assert($guidMan instanceOf GuidManager);
      $this->guidManager=$guidMan;
    }
  }

  function start() {
    $this->conMan->check();

		$this->docType=sprintf("FATCA%s%s",$this->isTest?"1":"",$this->corrDocRefId?"2":"1");

		// Sanity check
		// docType: described in xsd for DocRefId
		// FATCA1: New Data, FATCA2: Corrected Data, FATCA3: Void Data, FATCA4: Amended Data
		// FATCA11: New Test Data, FATCA12: Corrected Test Data, FATCA13: Void Test Data, FATCA14: Amended Test Data
		$docTypeValid=array("FATCA11","FATCA12","FATCA13","FATCA14","FATCA1","FATCA2","FATCA3","FATCA4");
		if(!in_array($this->docType,$docTypeValid)) throw new \Exception("Unsupported docType. Please use: ".implode(", ",$docTypeValid));


		// following http://www.irs.gov/Businesses/Corporations/FATCA-XML-Schema-Best-Practices-for-Form-8966
		// the hash in an address should be replaced
		$this->data=array_map(function($x) {
      if($x["ENT_TYPE"]!="Individual") return $x;
			$reps=array(":","#",",","-",".","--","/");
			$x['ENT_ADDRESS']=Utils::cleanAddress($x['ENT_ADDRESS']);
			$x['ENT_FATCA_ID']=Utils::cleanTin($x['ENT_FATCA_ID']);
			return $x;
		}, $this->data);

    //Code playing with timezone
    //    date_default_timezone_set('UTC');
    //    $ts1=time();
    //    $ts2=strftime("%Y-%m-%dT%H:%M:%SZ",$ts1); 
    //    $tz=date_default_timezone_get();
    //    #var_dump($ts1,$ts2,$tz);
    //    $this->assertTrue($tz=="UTC");
		$this->ts3=strftime("%Y-%m-%dT%H:%M:%S", $this->ts); 
	}

  function filterIndividuals() {
		$o = array_filter(
      $this->data,
      function($x) {
        return $x["ENT_TYPE"]=="Individual";
      });
    if(count($o)<count($this->data)) {
      trigger_error("FatcaDataArray is not suitable for entries that have type!=Individual. Filtering and dropping ".(count($this->data)-count($o))." entries. Please use FatcaDataOecd instead", E_USER_WARNING);
    }
    return $o;
  }

	function toHtml() {
		$dv=$this->filterIndividuals();
		$dv=array_values($dv);
    $headers=array(
      "ENT_TYPE","ENT_LASTNAME","ENT_FIRSTNAME","ENT_FATCA_ID","ENT_ADDRESS",
      "ResidenceCountry","ENT_COD","posCur","Compte","cur","dvdCur","intCur");

    // assert that there no keys more than those defined above
    array_map(function($x) use($headers) {
      $ad=array_diff(array_keys($x),$headers);
      assert(count($ad)==0,"Test no extra headers failed. Found: ".implode(", ",$ad));
    }, $dv);

	  $th=implode(array_map(function($x) { return "<th>".$x."</th>"; },$headers));
    $body=array();
    foreach($dv as $y) {
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
  		$di=$this->filterIndividuals();

	    # convert to xml 
	    #        xsi:schemaLocation='urn:oecd:ties:fatca:v1 FatcaXML_v1.1.xsd'
	    $diXml=sprintf("
		<ftc:FATCA_OECD version='1.1'
		    xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance'
		    xmlns:sfa='urn:oecd:ties:stffatcatypes:v2'
		    xmlns:ftc='urn:oecd:ties:fatca:v2'>
		    <ftc:MessageSpec>
			<sfa:SendingCompanyIN>".$this->conMan->config["ffaid"]."</sfa:SendingCompanyIN>
			<sfa:TransmittingCountry>LB</sfa:TransmittingCountry>
			<sfa:ReceivingCountry>US</sfa:ReceivingCountry>
			<sfa:MessageType>FATCA</sfa:MessageType>
			<sfa:Warning/>
			<sfa:MessageRefId>%s</sfa:MessageRefId>
			<sfa:ReportingPeriod>".sprintf("%s-12-31",$this->taxYear)."</sfa:ReportingPeriod>
			<sfa:Timestamp>$this->ts3</sfa:Timestamp>
		    </ftc:MessageSpec>
		    <ftc:FATCA>
			<ftc:ReportingFI>
			    <sfa:Name>FFA Private Bank</sfa:Name>
			    <sfa:Address>
				<sfa:CountryCode>LB</sfa:CountryCode>
				<sfa:AddressFree>Foch street</sfa:AddressFree>
			    </sfa:Address>
			    <ftc:DocSpec>
				<ftc:DocTypeIndic>%s</ftc:DocTypeIndic>
				<ftc:DocRefId>%s</ftc:DocRefId>
				%s
			    </ftc:DocSpec>
			</ftc:ReportingFI>
		    <ftc:ReportingGroup>
		    %s
		    </ftc:ReportingGroup>
		    </ftc:FATCA>
		</ftc:FATCA_OECD>",
		$this->guidManager->get(),
		$this->docType, // not sure about this versus the same entry below
    // based on http://www.irs.gov/Businesses/Corporations/FATCA-XML-Schemas-Best-Practices-for-Form-8966-DocRefID
    // as of June 2016, I can no longer just do this: 		$this->guidManager->guidPrepd[2],
		$this->guidManager->get(), 
		!$this->corrDocRefId?"":sprintf("<ftc:CorrDocRefId>%s</ftc:CorrDocRefId>",$this->corrDocRefId), 
		implode(array_map(
		    function($x) {
			// TIN default  issuedBy='US'
			return sprintf("
			    <ftc:AccountReport>
			    <ftc:DocSpec>
			    <ftc:DocTypeIndic>%s</ftc:DocTypeIndic>
			    <ftc:DocRefId>%s</ftc:DocRefId>
			    </ftc:DocSpec>
			    <ftc:AccountNumber>%s</ftc:AccountNumber>
			    <ftc:AccountHolder>
			    <ftc:Individual>
				<sfa:TIN>%s</sfa:TIN>
				<sfa:Name>
				    <sfa:FirstName>%s</sfa:FirstName>
				    <sfa:LastName>%s</sfa:LastName>
				</sfa:Name>
				<sfa:Address>
				    <sfa:CountryCode>%s</sfa:CountryCode>
				    <sfa:AddressFree>%s</sfa:AddressFree>
				</sfa:Address>
			    </ftc:Individual>
			    </ftc:AccountHolder>
			    <ftc:AccountBalance currCode='%s'>%s</ftc:AccountBalance>
          %s
          %s
			    </ftc:AccountReport>
			",
			$this->docType, // check the xsd
      // based on http://www.irs.gov/Businesses/Corporations/FATCA-XML-Schemas-Best-Practices-for-Form-8966-DocRefID
      // as of June 2016, I can no longer just do this: $this->guidManager->guidPrepd[4],
			$this->guidManager->get(), 
			$x['Compte'],
			$x['ENT_FATCA_ID'],
			$x['ENT_FIRSTNAME'],
			$x['ENT_LASTNAME'],
			$x['ResidenceCountry'],
			$x['ENT_ADDRESS'],
			$x['cur'],
			$x['posCur'],
      array_key_exists('dvdCur',$x)?sprintf("<ftc:Payment><ftc:Type>FATCA501</ftc:Type><ftc:PaymentAmnt currCode='%s'>%s</ftc:PaymentAmnt></ftc:Payment>",$x['cur'],floor($x['dvdCur'])):"",
      array_key_exists('intCur',$x)?sprintf("<ftc:Payment><ftc:Type>FATCA502</ftc:Type><ftc:PaymentAmnt currCode='%s'>%s</ftc:PaymentAmnt></ftc:Payment>",$x['cur'],floor($x['intCur'])):""
			); },
		    $di
		),"\n")
	    );

		// utf8
		if($utf8) $diXml=utf8_encode($diXml);

		// drop all spaces ... 
		// This is probably unnecessary, but I had thought that the security threat alert we were receiving was because of this
		// I later learned that this was not the reason, but I'm leaving it anyway
		$doc = new \DOMDocument();
		$doc->preserveWhiteSpace = false;
		$doc->formatOutput = true;//false;
		$doc->loadXML($diXml);
		$diXml=$doc->saveXML();

	    return $diXml;
	}

  function getIsTest() { return $this->isTest; }

  function getGiinSender() { return $this->conMan->config["ffaid"]; }

  function getTaxYear() {
    return $this->taxYear;
  }

  function getTsBase() {
    return $this->ts;
  }


} // end class
