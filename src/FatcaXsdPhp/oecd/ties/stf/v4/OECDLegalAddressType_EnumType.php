<?php
namespace oecd\ties\stf\v4;

/**
 * @xmlNamespace urn:oecd:ties:stf:v4
 * @xmlType token
 * @xmlName OECDLegalAddressType_EnumType
 * @var oecd\ties\stf\v4\OECDLegalAddressType_EnumType
 * @xmlDefinition This is a datatype for an attribute to an address. It serves to indicate the legal character of that address (residential, business etc.)
			Possible values are:
			OECD301=residentialOrBusiness
			OECD302=residential
			OECD303=business
			OECD304=registeredOffice
			OECD305=unspecified	
			
 */
class OECDLegalAddressType_EnumType
	{

		/**
		 * @xmlType value
		 * @var string
		 */
		public $value;

} // end class OECDLegalAddressType_EnumType
