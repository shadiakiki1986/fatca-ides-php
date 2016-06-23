<?php

namespace FatcaIdesPhp;

class UrlOpener {

  function download($cache,$url) {
    file_put_contents($cache,fopen($url, 'r'));
  }

}
