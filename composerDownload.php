<?php

require __DIR__."/vendor/autoload.php";

#echo "Creating temp dir\n";
#$fnH = tempnam("/tmp","");
#unlink($fnH);
#mkdir($fnH);
$fnH="cache";

echo "Downloading into $fnH\n";
$dl = new \FatcaIdesPhp\Downloader($fnH);
$dl->download();
echo "Done\n";
