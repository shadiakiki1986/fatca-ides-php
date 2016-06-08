# FatcaIdesPhp

[![Build Status](https://travis-ci.org/shadiakiki1986/FatcaIdesPhp.svg?branch=master)](http://travis-ci.org/shadiakiki1986/FatcaIdesPhp)

This is a PHP library that converts bank client data to FATCA files submittable via the IDES gateway.
If you find in this library any code that is specific to the Financial Institution for which I did this work,
do not hesitate to point them out to me by opening an [issue](https://github.com/shadiakiki1986/FatcaIdesPhp/issues).

For more information check the [IRS FATCA IDES Technical FAQs](http://www.irs.gov/Businesses/Corporations/FATCA-IDES-Technical-FAQs)

For other language implementations, please check the [IRS github page](https://github.com/IRSgov)
 

# Pre-requisites
* php
* client data in php array form
* SSL certificate for your financial institution
* Private and public keys used to get the SSL certificate

# Installation instructions
1. Add the following to your composer.json file

```php
{
  "repositories": [
    {
      "url": "https://github.com/shadiakiki1986/FatcaIdesPhp",
      "type": "git"
    }
  ],
  "require": {
    "shadiakiki1986/FatcaIdesPhp": "dev-default"
  }
}
```

2. Run `composer install`
3. Enable the `mcrypt` extension in php
```bash
    [sudo] apt-get install php5-mcrypt
    [sudo] php5enmod mcrypt
    [sudo] service apache2 restart
```

4. Download the financial institution's ssl certificate, private key, and public key
 * Note that the public key can be extracted from the certificate (on the TODO to be implied without the need for the user to specify it)

# Examples
For a complete example of how to use this library, please check [IDES-Data-Preparation-Php](https://github.com/shadiakiki1986/IDES-Data-Preparation-Php). Here I just list an example of how to generate a FATCA zip file for submission on the IDES gateway

```php
require __DIR__.'/vendor/autoload.php';
use FatcaIdesPhp\Transmitter;

// set tax year
$taxYear=2014;

// example client data
$di=array(
  array("Compte"=>"1234","ENT_FIRSTNAME"=>"Clyde","ENT_LASTNAME"=>"Barrow","ENT_FATCA_ID"=>"123-1234-123","ENT_ADDRESS"=>"Some street somewhere","ResidenceCountry"=>"US","posCur"=>100000000,"cur"=>"USD"),
  array("Compte"=>"5678","ENT_FIRSTNAME"=>"Bonnie","ENT_LASTNAME"=>"Parker","ENT_FATCA_ID"=>"456-1234-123","ENT_ADDRESS"=>"Dallas, Texas","ResidenceCountry"=>"US","posCur"=>100,"cur"=>"LBP")
);

// config with paths to files downloaded
$config = array(
  # SSL certificate bought using the private key
  "FatcaCrt" => "/var/lib/IDES/keys/ssl_certificate.crt"),
  # Private key used to get the SSL certificate
  "FatcaKeyPrivate" => "/var/lib/IDES/keys/institution-fatca-private.pem",
  # public key extracted out of private key above
  "FatcaKeyPublic" => "/var/lib/IDES/keys/institution-fatca-public.pem",

  # download folder for IRS public key and schema files
  "downloadFolder" => '/var/lib/IDES/downloads/',

  # Fatca Sender GIIN
  "ffaid": 'A1BBCD.00000.XY.123',
  # Fatca Receiver GIIN, e.g. IRS
  "ffaidReceiver": '000000.00000.TA.840'

);


$fca=Transmitter::shortcut($di,false,null,$taxYear,"zip","",$config);
$fca->getZip();
```

# License
Please check [[LICENSE]]

# Testing
```bash
composer install
composer run-script test
```

