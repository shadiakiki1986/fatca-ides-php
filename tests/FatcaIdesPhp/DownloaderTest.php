<?php

namespace FatcaIdesPhp;

class DownloaderTest extends \PHPUnit_Framework_TestCase {

    public function setUp() {
      $this->gm=new Downloader();
    }

    public function testTempdir() {
      $this->assertTrue(is_dir($this->gm->downloadFolder));
      $this->assertTrue(!is_file($this->gm->downloadFolder));
    }

    public function testCheckAvailable() {
      $this->gm->checkAvailable();
      $exist=array_column($this->gm->links,"exists");
      $this->assertEquals(count(array_filter($exist,function($x) { return $x; })),0);
    }

    public function testDownload() {
      $this->gm->download();

      $this->gm->checkAvailable();
      $exist=array_column($this->gm->links,"exists");
      $this->assertEquals(count(array_filter($exist,function($x) { return !$x; })),0);
    }

    public function testAsTransmitterConfig() {
      $this->gm->download();
      $o = $this->gm->asTransmitterConfig();
      $exist = array_map(function($x) { return file_exists($x); },$o);
      $missing = array_filter($exist,function($x) { return !$x; });
      $this->assertEquals(count($missing),0);
    }

}

