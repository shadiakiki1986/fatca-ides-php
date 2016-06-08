# IDES-Data-Preparation-Php
The IDES Data Preparation Php project repository is a PHP package that generates FATCA files submittable via the IDES gateway.
Please note that this implementation is specific to the Financial Institution for which I did this work.
There may be a need for modifications to tailor it for your needs.

For more information check http://www.irs.gov/Businesses/Corporations/FATCA-IDES-Technical-FAQs

For other language implementations, please check https://github.com/IRSgov

# Pre-requisites
* a function lib/getFatcaData.php that returns client data to be submitted for FATCA
* SSL certificate for your financial institution
* Private and public keys used to get the SSL certificate
* php5, apache2, ...

# Installation instructions
Here is how to install this library into a PHP project with a simple html page. It is a description of what the Dockerfile in the `sample-webApp` does

1. Add the following to your composer.json file

```php
{
  "repositories": [
    {
      "url": "https://github.com/shadiakiki1986/IDES-Data-Preparation-Php",
      "type": "git"
    }
  ],
  "require": {
    "shadiakiki1986/IDES-Data-Preparation-Php": "dev-default"
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

4. Download Fatca XML schema file, Sender metadata stylesheet, and IRS public key
 * links available at http://www.irs.gov/Businesses/Corporations/FATCA-XML-Schemas-and-Business-Rules-for-Form-8966
5. Download the remaining files indicated above in the Pre-Requisites section
6. Copy the file in config-sample.yml in the root folder to config.yml
7. Edit the paths in config.yml to match with the installation/download locations

# License
Please check [[LICENSE]]

# Developer notes
## Testing
`phpunit tests`

## Publishing
* Publish the contents of var/www in apache2 by using the sample config file provided in etc/apache2/sites-available/IDES-Data-Preparation-Php-sample.conf
 * Refer to standard apache2 guides on publishing websites
* Navigate in your browser to http://your-server/IDES-Data-Preparation-Php and get your data in html, xml, or zip format
* create the folder defined in config.php under ZipBackupFolder
 * and don''t forget to chown www-data:www-data
* can also use `var/www/api/transmitter.php` from CLI
 * run `php var/www/api/transmitter.php --help` for options
