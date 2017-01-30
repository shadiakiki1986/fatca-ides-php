#!/bin/bash
# Update FATCA schema files and IRS public key
# Links below from
# https://www.irs.gov/businesses/corporations/fatca-xml-schemas-and-business-rules-for-form-8966

set -e

# save in temporary folder
BASE=`mktemp -d`
ROOT=`dirname "$0"`

# FATCA main schema
#URL=https://www.irs.gov/pub/fatca/FATCAXMLSchemav1.zip
URL="https://www.irs.gov/pub/fatca/fatcaxml_v2.0.zip"
wget $URL -O $BASE/fatcaxml.zip
mkdir -p $BASE/fatcaxml
unzip $BASE/fatcaxml.zip -d $BASE/fatcaxml
rm $BASE/fatcaxml.zip
mv $BASE/fatcaxml/FatcaXML_v2.0.xsd $BASE/fatcaxml/FatcaXML.xsd
mv $BASE/fatcaxml/isofatcatypes_v1.1.xsd $BASE/fatcaxml/isofatcatypes.xsd
mv $BASE/fatcaxml/oecdtypes_v4.2.xsd $BASE/fatcaxml/oecdtypes.xsd
mv $BASE/fatcaxml/stffatcatypes_v2.0.xsd $BASE/fatcaxml/stffatcatypes.xsd

# Meta schema
URL="https://www.irs.gov/pub/fatca/SenderMetadatav1.1.zip"
wget $URL -O $BASE/sender.zip
mkdir -p $BASE/SenderMetadata
unzip $BASE/sender.zip -d $BASE/SenderMetadata
rm $BASE/sender.zip

# IRS certificate
URL="https://www.ides-support.com/Downloads/encryption-service_services_irs_gov.crt"
wget $URL -O $BASE/encryption-service_services_irs_gov.crt

#
rm -rf fatcaxml fatcaxml.zip SenderMetadata sender.zip encryption-service_services_irs_gov.crt

# move
rm -rf $ROOT/{fatcaxml,SenderMetadata}
mv $BASE/* $ROOT/
