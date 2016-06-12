<?php
namespace oecd\ties\isofatcatypes\v1;

/**
 * @xmlNamespace urn:oecd:ties:isofatcatypes:v1
 * @xmlType string
 * @xmlName currCode_Type
 * @var oecd\ties\isofatcatypes\v1\currCode_Type
 * @xmlDefinition 
			The appropriate currency code from the ISO 4217 three-byte alpha version for the currency in which a monetary amount is expressed. 
--- The former DEM - Germany, Deutsche Mark - was temporarily added to allow data from earlier years still to be transmitted ---
			
 */
class currCode_Type
	{

		/**
		 * @xmlType value
		 * @var string
		 */
		public $value;

} // end class currCode_Type
