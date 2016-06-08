2016-06-07
* convert to php library installable with composer
* split out sample web-app to subdirectory
* write dockerfile and push to hub.docker.com
* correct documentation
* write proper tests and add travis-ci
* publish with composer?
* generate snake-oil certificate and self-signed keys

2016-01-01
* in var/www/api/transmitter.php I have a format=metadata but I don''t document it in the file header
* renaming the files for emailing is skipping the xmls?

2015-01-01
* test FATCA12, 13, 14, as http://www.irs.gov/pub/irs-pdf/p5124.pdf
 * How to reference a previously sent submission for a correction?
 * this is FATCA1, 2, 3, 4
 * http://www.irs.gov/pub/irs-utl/Pub5124UserGuide.pdf
 * I already tried to start implementing this, but it is incomplete because the documentation isn''t so clear
