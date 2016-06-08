2016-06-08
* should be able to extract the public key from the certificate instead of requiring the user to do
 * `openssl x509 -pubkey -noout -in cert.pem  > pubkey.pem`
* add coveralls tag
 * https://coveralls.io/github/shadiakiki1986/FatcaIdesPhp

2016-06-07
* continue fixing tests

2015-01-01
* test FATCA12, 13, 14, as http://www.irs.gov/pub/irs-pdf/p5124.pdf
 * How to reference a previously sent submission for a correction?
 * this is FATCA1, 2, 3, 4
 * http://www.irs.gov/pub/irs-utl/Pub5124UserGuide.pdf
 * I already tried to start implementing this, but it is incomplete because the documentation isn''t so clear
