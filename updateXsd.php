<?php

require __DIR__."/vendor/autoload.php";

echo "Converting xsd to php\n";
use com\mikebevz\xsd2php\LegkoXML;
$legko = new LegkoXML();
$schema = __DIR__."/assets/fatcaxml/FatcaXML.xsd";
$dest = "src/FatcaXsdPhp";
assert(is_dir($dest));
$legko->compileSchema($schema, $dest);

exec("sed -i 's/<?php/<?php namespace FatcaXsdPhp;/g' $dest/*php"); // $dest/oecd/ties/isofatcatypes/v1/*php $dest/oecd/ties/stf/v4/*php $dest/oecd/ties/stffatcatypes/v1/*php");
echo "Done\n";
