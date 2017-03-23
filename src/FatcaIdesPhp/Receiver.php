<?php

namespace FatcaIdesPhp;

class Receiver {

var $data; // php data with fatca information
var $dataXml;
var $dataXmlSigned;
var $dataCompressed;
var $dataEncrypted;
var $diDigest;
var $tf1;
var $tf2;
var $tf3;
var $tf4;
var $ts;
var $file_name;

// config: php array
// tf4: temp file?
function __construct($conMan,$rm,$tf4=false) {
  $this->tf4=$tf4;
  assert($conMan instanceOf ConfigManager);
  $this->conMan=$conMan;
  assert($rm instanceOf RsaManager);
  $this->rm=$rm;
}

function start() {
  $this->conMan->check();
  
	if(!$this->tf4) {
		$this->tf4=sys_get_temp_dir();
		$temp_file = tempnam(sys_get_temp_dir(), 'Tux');
		unlink($temp_file);
		mkdir($temp_file);
		$this->tf4=$temp_file;
	} else {
    if(!file_exists($this->tf4)) {
      throw new \Exception(sprintf("Passed folder inexistant: '%s'",$this->tf4));
    }
  }

	$this->tf1=tempnam("/tmp","");
	$this->tf2=tempnam("/tmp","");
	$this->tf3=tempnam("/tmp","");

	$this->ts=time();
	$this->ts2=strftime("%Y-%m-%dT%H:%M:%SZ",$this->ts);
}

function fromZip($filename) {
	$zip = new \ZipArchive();
	if ($zip->open($filename) === TRUE) {
	    $zip->extractTo($this->tf4);
	    $zip->close();
	} else {
	    throw new \Exception(sprintf("failed to open archive: '%s'",$filename));
	}

	$xx=scandir($this->tf4);
	$this->files["payload"]=array_values(preg_grep("/.*_Payload/",$xx));
	$this->files["payload"]=$this->files["payload"][0];
	$this->rm->from=preg_replace("/(.*)_Payload/","$1",$this->files["payload"]);
	$this->files["key"]=array_values(preg_grep("/.*_Key/",$xx));
	$this->files["key"]=$this->files["key"][0];
	$this->rm->to  =preg_replace("/(.*)_Key/","$1",$this->files["key"]);

	  $fp=fopen($this->tf4."/".$this->files["key"],"r");
	  $this->rm->aesEncrypted=fread($fp,8192);
	  fclose($fp);
}

	function fromEncrypted() {
		$fp=fopen($this->tf4."/".$this->files["payload"],"r");
		$this->dataEncrypted=fread($fp,8192);
		fclose($fp);

    $this->dataCompressed = $this->rm->am->decrypt($this->dataEncrypted);
	}

	function fromCompressed() {
		$tf3=tempnam("/tmp","");
		file_put_contents($tf3,$this->dataCompressed);

		$zip = new \ZipArchive();
		if ($zip->open($tf3) === TRUE) {
			$this->dataXmlSigned=$zip->getFromIndex(0);
			$zip->close();
		} else {
		    throw new \Exception('failed to read compressed data');
		}
	}

} // end class
