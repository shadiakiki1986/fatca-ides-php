# fatca-ides-php

[![Build Status](https://travis-ci.org/shadiakiki1986/fatca-ides-php.svg?branch=master)](http://travis-ci.org/shadiakiki1986/fatca-ides-php)

[packagist](https://packagist.org/packages/shadiakiki1986/fatca-ides-php)

This is a PHP library that converts bank client data to FATCA files submittable via the IDES gateway.
If you find in this library any code that is specific to the Financial Institution for which I did this work,
do not hesitate to point them out to me by opening an [issue](https://github.com/shadiakiki1986/fatca-ides-php/issues).

For more information check the [IRS FATCA IDES Technical FAQs](http://www.irs.gov/Businesses/Corporations/FATCA-IDES-Technical-FAQs)

For other language implementations, please check the [IRS github page](https://github.com/IRSgov)
 

# Pre-requisites
* php
* client data in php array form
* SSL certificate for your financial institution
* Private and public keys used to get the SSL certificate

# Installation instructions
Install [composer](https://getcomposer.org/download/)
```bash
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('SHA384', 'composer-setup.php') === '55d6ead61b29c7bdee5cccfb50076874187bd9f21f65d8991d46ec5cc90518f447387fb9f76ebae1fbbacf329e583e30') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```

Install php extensions
```bash
sudo apt-get install php7.0-xml php-mbstring php-zip php-mcrypt php-pear libyaml-dev php-dev php-bcmath
sudo pecl install yaml-2.0.0
sudo echo "extension=yaml.so" > /etc/php/7.0/mods-available/yaml.ini
sudo ln -s /etc/php/7.0/mods-available/yaml.ini /etc/php/7.0/cli/conf.d/20-yaml.ini

composer require shadiakiki1986/fatca-ides-php
composer require swiftmailer/swiftmailer # To enable sending emails
composer install
[sudo] apt-get install php5-mcrypt
[sudo] php5enmod mcrypt
[sudo] service apache2 restart # needed for web applications served with apache
```

If emails are enabled, pass your config (similar to [this](http://symfony.com/doc/current/cookbook/email/email.html#configuration) ) to the `toEmail` function of `Transmitter` class (check example below)

Next, download the financial institution's ssl certificate, private key, and public key.
Note that the public key can be extracted from the certificate (on the TODO to be implied without the need for the user to specify it)

# Examples
For a complete example of how to use this library, please check [IDES-Data-Preparation-Php](https://github.com/shadiakiki1986/IDES-Data-Preparation-Php). Here I just list an example of how to generate a FATCA zip file for submission on the IDES gateway

```php
require __DIR__.'/vendor/autoload.php';
use FatcaIdesPhp\Transmitter;

// set tax year
$taxYear=2014;

// example client data
$di=array(
  array("Compte"=>"1234","ENT_FIRSTNAME"=>"Clyde","ENT_LASTNAME"=>"Barrow","ENT_FATCA_ID"=>"123-1234-123","ENT_ADDRESS"=>"Some street somewhere","ResidenceCountry"=>"US","posCur"=>100000000,"cur"=>"USD","ENT_TYPE"=>"Individual"),
  array("Compte"=>"5678","ENT_FIRSTNAME"=>"Bonnie","ENT_LASTNAME"=>"Parker","ENT_FATCA_ID"=>"456-1234-123","ENT_ADDRESS"=>"Dallas, Texas","ResidenceCountry"=>"US","posCur"=>100,"cur"=>"LBP","ENT_TYPE"=>"Individual")
);

// config with paths to files downloaded
$config = array(
  # SSL certificate bought using the private key
  "FatcaCrt" => "/var/lib/IDES/keys/ssl_certificate.crt",
  # Private key used to get the SSL certificate
  "FatcaKeyPrivate" => "/var/lib/IDES/keys/institution-fatca-private.pem",
  # public key extracted out of private key above
  "FatcaKeyPublic" => "/var/lib/IDES/keys/institution-fatca-public.pem",

  # Fatca Sender GIIN
  "ffaid" => 'A1BBCD.00000.XY.123',
  # Fatca Receiver GIIN, e.g. IRS
  "ffaidReceiver" => '000000.00000.TA.840',

  # email configuration if Transmitter::toEmail is used
  "swiftmailer" => array(
    "host" => "my.host.com",
    "port" => 12345,
    "username" => "myusername",
    "password" => "mypassword",
    "name" => "My Name",
    "reply" => "myemail@host.com")

);

$factory = new \FatcaIdesPhp\Factory();

$fda = new \FatcaIdesPhp\FatcaDataArray($di);
$fdo = $factory->array2oecd($fda); // , false, null, $taxYear, "zip", "", $config
$tmtr=$factory->transmitter($fdo);
$tmtr->getZip();
```

To submit a correction XML file, as above, but use
```php
$sxe = simplexml_load_file("path/to/xml");
$fdx = new \FatcaIdesPhp\FatcaDataXml($sxe);
$tmtr=$factory->transmitter($fdx);
```


# License
Please check [[LICENSE]]

# Testing
```bash
composer install
composer run-script test
```

# Schema versions
## Current state
The schema files and IRS public key are committed to this repo in `assets`.

The committed versions are the 2.0 versions.

This package was tested succesfully against the IDES test gateway on Jan 31, 2017.
The test included
* submitting test data to the server via SFTP using the [IDES-Data-Preparation-Php](https://github.com/shadiakiki1986/IDES-Data-Preparation-Php) package
* receiving an ACK for the submission via email
* receiving a response from the IRS using the [IDES-Data-Preparation-Php](https://github.com/shadiakiki1986/IDES-Data-Preparation-Php) package

## Updating

To update them, run the following two scripts:
1. `./assets/update.sh`: Downloads files from IRS website
  * Note that the URL's in the script may need to be updated because newer versions are usually uploaded to new endpoints
  * Note also that there are some file renaming lines in the script so that I can diff versions
    * e.g. `git diff ba722d8bcda61f657529a67cdbec873a29dc7d70 5f9545b565ddf0d41997b29c704c3990813f4bb8` will diff version 1.1 and 2.0

2. `php updateXsd.php`: converts the schema files to PHP classes in `src/FatcaXsdPhp`
  * I moderate the updates and commit them to the repo if suitable
  * Open issues on these are:
    * I currently fix the below issues manually after the update
    * There seems to be a problem in `src/FatcaXsdPhp/oecd/ties/stffatcatypes/v1/Address_Type.php` by having two AddressFree fields
    * `@xmlNamespace urn:oecd:ties:fatca:v1` is missing from `src/FatcaXsdPhp/FATCA_OECD` pending https://github.com/moyarada/XSD-to-PHP/issues/36
    * The AddressFree field has the wrong namespace: should be urn:oecd:ties:stffatcatypes:v1 instead of `urn:oecd:ties:fatca:v1`
    * ReportingGroup.php is not generated + its `@var` is missing
    * `src/FatcaXsdPhp/oecd/ties/stffatcatypes/v1/NamePerson_Type.php`
      * @xmlNamespace urn:oecd:ties:fatca:v1
      * changed manually to
      * @xmlNamespace urn:oecd:ties:stffatcatypes:v1
