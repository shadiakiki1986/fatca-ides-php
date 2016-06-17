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
    $hosts = array(
      "live"=>array("host"=>"www.idesgateway.com","port"=>4022),
      "test"=>array("host"=>"wwwpse.idesgateway.com","port"=>4022)
    );
    if(!array_key_exists($hostType,$hosts)) throw new \Exception("Invalid host type passed '".$hostType."'. Please use 'live' or 'test'");

    $hi = $hosts[$hostType];
    $sftp = new \phpseclib\Net\SFTP($hi["host"],$hi["port"]);
    return $sftp;
  }

  function login($username,$password) {
    $this->log->info("Connecting to sftp ".$this->sftp->host.":".$this->sftp->port);
    if(!$this->sftp->login($username, $password)) {
      return 'Login Failed for '.$username.' on '.$this->sftp->host.':'.$this->sftp->port.' to upload zip file';
    }
    $this->log->info("Logged in with ".$username);
    return false;
  }

  function put($zipfile,$destName=null) {
    if(is_null($destName)) $destName=basename($zipfile);
    assert($this->sftp->isAuthenticated(),"sftp session is authenticated test");
    if(!file_exists($zipfile)) return("Zip file inexistant '".$zipfile."'");

    // check that this is a zip file
    // http://stackoverflow.com/a/10368236/4126114
    $ext = pathinfo($zipfile, PATHINFO_EXTENSION);
    if($ext!="zip") return "Only zip files accepted. Rejecting '".$zipfile."'";

    // puts an x-byte file named filename.remote on the SFTP server,
    // where x is the size of filename.local
    $this->log->info("Uploading file '".$zipfile."' as '".$destName."'");
    $this->sftp->put(
      "Outbox/840/".$destName,
      $zipfile, 
      \phpseclib\Net\SFTP::SOURCE_LOCAL_FILE);
    $this->log->info("Uploaded");

    // verify that it is uploaded
//    $nl = $this->sftp->nlist(".");
//    if(!in_array("Outbox",$nl)) return "/Outbox not available on sftp server";
//    $nl = $this->sftp->nlist("Outbox");
//    if(!in_array("840",$nl)) return "/Outbox/840 not available on sftp server";
    $nl = $this->sftp->nlist("Outbox/840");
    if(!in_array($destName,$nl)) return "/Outbox/840/".$destName." not available on sftp server";

    return false;
  }


} // end class
