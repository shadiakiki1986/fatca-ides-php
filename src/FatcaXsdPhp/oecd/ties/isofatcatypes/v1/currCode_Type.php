<?php
namespace oecd\ties\isofatcatypes\v1;

/**
 * @xmlNamespace urn:oecd:ties:isofatcatypes:v1
 * @xmlType string
 * @xmlName currCode_Type
 * @var oecd\ties\isofatcatypes\v1\currCode_Type
 * @xmlDefinition 
			The appropriate three-byte alpha currency code from ISO 4217 list to express currency for monetary amount. 
The former DEM - Germany, Deutsche Mark - was temporarily added to allow data from earlier years still to be transmitted.
			
 */
class currCode_Type
	{

		/**
		 * @xmlType value
		 * @var string
		 */
		public $value;

} // end class currCode_Type
