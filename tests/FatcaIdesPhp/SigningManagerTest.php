<?php

namespace FatcaIdesPhp;

class SigningManagerTest extends \PHPUnit\Framework\TestCase {

  public function setUp() {
    $conMan = $this->getMockBuilder('\FatcaIdesPhp\ConfigManager')
                   ->disableOriginalConstructor()
                   ->getMock();
    $conMan->config=array(
      "FatcaKeyPrivate"=>__DIR__."/../../vendor/robrichards/xmlseclibs/tests/privkey.pem",
      "FatcaCrt"=>__DIR__."/../../vendor/robrichards/xmlseclibs/tests/mycert.pem",
      // below public key extracted manually from certificate above using
      // openssl x509 -pubkey -noout -in mycert.pem  > pubkey.pem
      "FatcaKeyPublic"=>__DIR__."/pubkey.pem"
    );

    $this->sm = new SigningManager($conMan);
  }

  public function testSignSpacesNo() {
    $signed = $this->sm->sign('<bla><something>else</something></bla>');
    $verified=$this->sm->verify($signed);
    $this->assertTrue($verified);
  }

  public function testSignSpacesYes() {
    $signed = $this->sm->sign('<bla>
      <something>else</something>
    </bla>');
    $verified=$this->sm->verify($signed);
    $this->assertTrue($verified);
  }

}
