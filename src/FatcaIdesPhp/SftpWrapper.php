<?php

namespace FatcaIdesPhp;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

# http://phpseclib.sourceforge.net/sftp/examples.html#put
class SftpWrapper {

	function __construct($sftp,$LOG_LEVEL=Logger::WARNING) {
    assert($sftp instanceof \phpseclib\Net\SFTP);
    $this->sftp=$sftp;

    $this->log = new Logger('SftpWrapper');
    // http://stackoverflow.com/a/25787259/4126114
    $this->log->pushHandler(new StreamHandler('php://stdout', $LOG_LEVEL)); // <<< uses a stream
	}

  function __destruct() {
    if($this->sftp->isConnected()) {
      $this->log->info("Disconnecting from sftp");
      $this->sftp->disconnect();
    }
  }

  public static function getSFTP($hostType) {
    $this->hosts = array(
      "live"=>array("host"=>"www.idesgateway.com","port"=>4022),
      "test"=>array("host"=>"wwwpse.idesgateway.com","port"=>4022)
    );
    if(!array_key_exists($hostType,$this->hosts)) throw new \Exception("Invalid host type passed '".$hostType."'. Please use 'live' or 'test'");

    $hi = $this->hosts[$this->hostType];
    $sftp = new SFTP($hi["host"],$hi["port"]);
    return $sftp;
  }

  function login($username,$password) {
    $this->log->info("Connecting to sftp");
    if(!$this->sftp->login($username, $password)) {
      throw new \Exception('Login Failed for '.$username);
    }
    $this->log->info("Logged in");
  }

  function put($zipfile) {
    assert($this->sftp->isAuthenticated(),"sftp session is authenticated test");
    if(!file_exists($zipfile)) throw new \Exception("Zip file inexistant '".$zipfile."'");

    // check that this is a zip file
    // http://stackoverflow.com/a/10368236/4126114
    $ext = pathinfo($zipfile, PATHINFO_EXTENSION);
    if($ext!="zip") throw new \Exception("Only zip files accepted. Rejecting '".$zipfile."'");

    // puts an x-byte file named filename.remote on the SFTP server,
    // where x is the size of filename.local
    $this->log->info("Uploading file '".$zipfile."'");
    $this->sftp->put(
      $zipfile, 
      "Outbox/840/".basename($zipfile), 
      \phpseclib\Net\SFTP::SOURCE_LOCAL_FILE);
    $this->log->info("Uploaded");

    // verify that it is uploaded
    $nl = $this->sftp->nlist(".");
    if(!in_array("Outbox",$nl)) throw new \Exception("/Outbox not available on sft server");
    $nl = $this->sftp->nlist("Outbox");
    if(!in_array("840",$nl)) throw new \Exception("/Outbox/840 not available on sft server");
    $nl = $this->sftp->nlist("Outbox/840");
    if(!in_array(basename($zipfile),$nl)) throw new \Exception("/Outbox/840/".basename($zipfile)." not available on sft server");
  }


} // end class
