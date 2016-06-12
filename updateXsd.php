<?php

require __DIR__."/vendor/autoload.php";

echo "Converting xsd to php\n";
use com\mikebevz\xsd2php\LegkoXML;
$legko = new LegkoXML();
$schema = $fnH."/FATCA XML Schema v1.1/FatcaXML_v1.1.xsd";
assert(file_exists($schema),"Didnt composerDownload.php get executed yet? Run composer install");
$dest = "src/FatcaXsdPhp";
assert(is_dir($dest));
$legko->compileSchema($schema, $dest);

exec("sed -i 's/<?php/<?php namespace FatcaXsdPhp;/g' $dest/*php"); // $dest/oecd/ties/isofatcatypes/v1/*php $dest/oecd/ties/stf/v4/*php $dest/oecd/ties/stffatcatypes/v1/*php");
echo "Done\n";
