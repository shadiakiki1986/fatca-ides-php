<?php

namespace FatcaIdesPhp;

// Implementation of FatcaDataInterface that is suitable for institutions that have only a flat array of individuals with reportable accounts
class FatcaDataArray {

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

  function __construct($dd, $isTest, $corrDocRefId, $taxYear, ConfigManager $conMan, GuidManager $guidMan=null) {

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
      $this->guidManager=$guidMan;
    }
  }

  // This is more of a cleaning function
  // It could have been part of the constructor
  // but the purpose was that the constructor do nothing
  public function start() {
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


} // end class
