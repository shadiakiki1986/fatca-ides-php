<?php

namespace FatcaIdesPhp;

class ReceiverTest extends PHPUnit_Framework_TestCase {

  public function testDir() {
    // http://stackoverflow.com/a/21473475/4126114
    $config=array();
    $user = posix_getpwuid(posix_getuid());
    $rx1=new Receiver($config,$user['dir']);
    $rx1->start(); // should pass since the user home directory is existant
    $this->assertTrue(true);
    $rx2=new Receiver($config,"/random/folder/inexistant/"); // should not pass since the directory is inexistant
    try {
      $rx2->start(); // should not pass since the directory is inexistant
      $this->assertTrue(false); // shouldnt get here
    } catch(\Exception $e) {
      $this->assertTrue(true); // should get here
    }
  }

  public function testWorkflow() {
    $fn="/home/shadi/samplefilefromides.zip";
    if(!file_exists($fn)) {
      $this->markTestSkipped("Zip file '%s' not available for testing",$fn);
      return;
    }
    $rx=new Receiver();
    $rx->start();
    $rx->fromZip($fn);
    $rx->decryptAesKey();
    $rx->fromEncrypted();
    $rx->fromCompressed();

    echo "From: ".$rx->from."\n";
    echo "To: ".$rx->to."\n";
    //echo "Key: ".$rx->aeskey."\n";
    //echo "Payload encrypted: ".$rx->dataEncrypted."\n";
    //echo "Payload decrypted: ".$rx->dataCompressed."\n";
    echo "Payload uncompressed: ".$rx->dataXmlSigned."\n";
  }
}
