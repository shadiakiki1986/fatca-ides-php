<?php

namespace FatcaIdesPhp;

interface FatcaDataInterface {
  public function start();
  public function toHtml();
  public function toXml($utf8);
  public function getMetadata();
  public function getIsTest();
  public function getGiinSender();
  public function getGiinReceiver();
}
