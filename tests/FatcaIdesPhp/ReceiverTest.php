<?php

namespace FatcaIdesPhp;

class ReceiverTest extends \PHPUnit_Framework_TestCase {

  public function setUp() {
    $this->conMan = $this->getMockBuilder('\FatcaIdesPhp\ConfigManager')
                         ->disableOriginalConstructor()
                         ->getMock();

    $this->rm = $this->getMockBuilder('\FatcaIdesPhp\RsaManager')
                         ->disableOriginalConstructor()
                         ->getMock();
  }

  public function testDir() {
    // http://stackoverflow.com/a/21473475/4126114
    $user = posix_getpwuid(posix_getuid());
    $rx1=new Receiver($this->conMan,$this->rm,$user['dir']);
    $rx1->start(); // should pass since the user home directory is existant
    $this->assertTrue(true);
    $rx2=new Receiver($this->conMan,$this->rm,"/random/folder/inexistant/"); // should not pass since the directory is inexistant
    try {
      $rx2->start(); // should not pass since the directory is inexistant
      $this->assertTrue(false); // shouldnt get here
    } catch(\Exception $e) {
      $this->assertTrue(true); // should get here
    }
  }

  public function testWorkflow() {
    // generate file with own public key for the sake of decrypting in ReceiverTest
    $zip=__DIR__."/data/testMocked_self.zip";
    $fdot = new FatcaDataOecdTest();
    $fdot->setUp();
    $fdot->conMan->config["FatcaIrsPublic"] = $fdot->conMan->config["FatcaKeyPublic"];

    if(false) {
      // generate zip file and save
      $ts1 = strtotime("2010-10-05 04:03:02");
      $ts2 = strftime("%Y-%m-%dT%H:%M:%S",$ts1);

      $fdot->oecd->MessageSpec->Timestamp=$ts2;
      // set sender GIIN as in config
      $fdot->oecd->MessageSpec->SendingCompanyIN = $fdot->conMan->config["ffaid"];

      $fdo=new FatcaDataOecd($fdot->oecd);

      $tmtr=Transmitter::shortcut($fdo,"html","",$fdot->conMan->config);
      copy($tmtr->tf4,$zip);
    }

    $rx=Receiver::shortcut($fdot->conMan->config,$zip);

//    echo "From: ".$rx->rm->from."\n";
//    echo "To: ".$rx->rm->to."\n";
//    //echo "Key: ".$rx->aeskey."\n";
//    //echo "Payload encrypted: ".$rx->dataEncrypted."\n";
//    //echo "Payload decrypted: ".$rx->dataCompressed."\n";
//    echo "Payload uncompressed: ".$rx->dataXmlSigned."\n";
    $this->assertTrue(!!$rx->dataXmlSigned);

  }
}
