2016-06-07
* converting the whole folder to a proper php package installable via composer
* splitting the sample web app into its own folder

2016-03-17
* converted scripts in tests to phpunit tests
 * these should be factored out to test the code in the Receiver and Transmitter classes instead of a copy of the code in the tests
* added some variable checking for the config
* minor documentation improvements

2015-07-02
* after a long struggle to understand why our submission was yielding a security threat alert, I learned that the way I was signing the XML file was wrong.
* Correcting it resulted in a successful submission
* I cleaned up the code a bit before making this commit. I probably should have made the messy commit right after the successful submission, and then made a 2nd cleaned up commit... too late now
* The people from lbi.fatca.ides@irs.gov were the ones who pointed out that my problem was with the signing

2015-06-26
* refactored ENT_COD to Compte and accountsTotalUsd to posCur
* had forgotten to include currency .. fixed

2015-06-12
* attempting at integrating correction doc ref ID, but documentation isn''t so clear
* added taxYear to getFatcaData and interface

2015-06-06
* data source functionality moved to separate function lib/getFatcaData.php
* refactored var/www/api/getFatcaClients.php to var/www/api/transmitter.php
* cleaned up code a bit

2015-06-04
* dropped ''xsi:schemaLocation'' which seems to have been causing ''virus threat''
* generating new ID''s instead of static one in MessageRefId
* setting timezone to UTC
* DocRefID: currently just putting ''Ref ID123''
* getting balances from Bankflow instead of dummy 0''s
* skipping some accounts just for testing purposes

2015-06-03
* testing in 2nd window
* got feedback saying that decrypted contents contain a virus threat
* working on the issue
* replaced xml signing with package xmlseclibs instead of custom function
* added class to parse received zip into xml
* replacing invalid characters

2015-05
* released first draft that successfully uploads
