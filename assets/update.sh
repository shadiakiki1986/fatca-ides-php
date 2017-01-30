#!/bin/bash
# Update FATCA schema files and IRS public key
# Links below from
# https://www.irs.gov/businesses/corporations/fatca-xml-schemas-and-business-rules-for-form-8966

set -e

# save in temporary folder
BASE=`mktemp -d`

# FATCA main schema
#URL=https://www.irs.gov/pub/fatca/FATCAXMLSchemav1.zip
URL="https://www.irs.gov/pub/fatca/fatcaxml_v2.0.zip"
wget $URL -O $BASE/fatcaxml.zip
mkdir -p $BASE/fatcaxml
unzip $BASE/fatcaxml.zip -d $BASE/fatcaxml
mv $BASE/"FATCA XML Schema v1.1/FatcaXML_v1.1.xsd" $BASE/FatcaXML.xsd
mv $BASE/"FATCA XML Schema v1.1/isofatcatypes_v1.0.xsd" $BASE/isofatcatypes.xsd
mv $BASE/"FATCA XML Schema v1.1/oecdtypes_v4.1.xsd" $BASE/oecdtypes.xsd
mv $BASE/"FATCA XML Schema v1.1/stffatcatypes_v1.1.xsd" $BASE/stffatcatypes.xsd

rm $BASE/fatcaxml.zip

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
mv $BASE/* .
