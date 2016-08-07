<?php

namespace FatcaIdesPhp;

class ReceiverTest extends \PHPUnit_Framework_TestCase {

  public function setUp() {
    $this->conMan = $this->getMockBuilder('\FatcaIdesPhp\ConfigManager')
                         ->disableOriginalConstructor()
                         ->getMock();

    $this->am = $this->getMockBuilder('\FatcaIdesPhp\AesManager')
                         ->disableOriginalConstructor()
                         ->getMock();
  }

  public function testDir() {
    // http://stackoverflow.com/a/21473475/4126114
    $user = posix_getpwuid(posix_getuid());
    $rx1=new Receiver($this->conMan,$this->am,$user['dir']);
    $rx1->start(); // should pass since the user home directory is existant
    $this->assertTrue(true);
    $rx2=new Receiver($this->conMan,$this->am,"/random/folder/inexistant/"); // should not pass since the directory is inexistant
    try {
      $rx2->start(); // should not pass since the directory is inexistant
      $this->assertTrue(false); // shouldnt get here
    } catch(\Exception $e) {
      $this->assertTrue(true); // should get here
    }
  }

  public function testWorkflow() {
    $fn=__DIR__."/data/testMocked_array.zip";
    $fdat = new FatcaDataArrayTest();
    $fdat->setUp();

    var_dump($fdat->conMan->config,$fn);
    $rx=Receiver::shortcut($fdat->conMan->config,$fn);

    echo "From: ".$rx->from."\n";
    echo "To: ".$rx->to."\n";
    //echo "Key: ".$rx->aeskey."\n";
    //echo "Payload encrypted: ".$rx->dataEncrypted."\n";
    //echo "Payload decrypted: ".$rx->dataCompressed."\n";
    echo "Payload uncompressed: ".$rx->dataXmlSigned."\n";
  }
}
