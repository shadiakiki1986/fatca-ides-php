<?php
namespace oecd\ties\stffatcatypes\v2;

/**
 * @xmlNamespace urn:oecd:ties:stffatcatypes:v2
 * @xmlType 
 * @xmlName Address_Type
 * @var oecd\ties\stffatcatypes\v2\Address_Type
 * @xmlDefinition The user may enter data about the party address either as (1) AddressFree (2) AddressFix or (3) a combination of both. If the user of a party either as one long field or to spread the data over up to eight elements or even to use both formats. If the user chooses the option to enter the data required in separate elements, the container element for this will be 'AddressFix'. If the user chooses the option to enter the data required in a less structured way in 'AddressFree' all available address details shall be presented as one string of bytes, blank or "/" (slash) or carriage return- line feed used as a delimiter between parts of the address. PLEASE NOTE that the address country code is outside both of these elements. Use AddressFix format to allow easy matching and use AddressFree if the sender cannot identify the different parts of the address. May use both formats, City element is required and and 'AddressFix' has to precede 'AddressFree'.
 */
class Address_Type
	{

	
	/**
	 * @Definition 2-character code for the country in the address
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v2
	 * @xmlName CountryCode
	 * @var oecd\ties\isofatcatypes\v1\CountryCode_Type
	 */
	public $CountryCode;
	/**
	 * @Definition Address in free text format
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v2
	 * @xmlName AddressFree
	 * @var oecd\ties\stffatcatypes\v2\StringMax4000_Type
	 */
	public $AddressFree;
	/**
	 * @Definition Address in predefined format
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v2
	 * @xmlName AddressFix
	 * @var oecd\ties\stffatcatypes\v2\AddressFix_Type
	 */
	public $AddressFix;
	/**
	 * @Definition Type of the address (e.g. residential, business)
	 * @xmlType attribute
	 * @xmlName legalAddressType
	 * @var oecd\ties\stf\v4\OECDLegalAddressType_EnumType
	 */
	public $legalAddressType;


} // end class Address_Type
