<?php
namespace oecd\ties\stffatcatypes\v1;

/**
 * @xmlNamespace urn:oecd:ties:stffatcatypes:v1
 * @xmlType 
 * @xmlName Address_Type
 * @var oecd\ties\stffatcatypes\v1\Address_Type
 * @xmlDefinition 
			The user has the option to enter the data about the address of a party either as one long field or to spread the data over up to eight  elements or even to use both formats. If the user chooses the option to enter the data required in separate elements, the container element for this will be 'AddressFix'. If the user chooses the option to enter the data required in a less structured way in 'AddressFree' all available address details shall be presented as one string of bytes, blank or "/" (slash) or carriage return- line feed used as a delimiter between parts of the address. PLEASE NOTE that the address country code is outside  both of these elements. The use of the fixed form is recommended as a rule to allow easy matching. However, the use of the free form is recommended if the sending state cannot reliably identify and distinguish the different parts of the address. The user may want to use both formats e.g. if besides separating the logical parts of the address he also wants to indicate a suitable breakdown into print-lines by delimiters in the free text form. In this case 'AddressFix' has to precede 'AddressFree'.
			
 */
class Address_Type
	{

	
	/**
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v1
	 * @xmlName CountryCode
	 * @var oecd\ties\isofatcatypes\v1\CountryCode_Type
	 */
	public $CountryCode;
	/**
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v1
	 * @xmlName AddressFree
	 * @var string
	 */
	public $AddressFree;
	/**
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v1
	 * @xmlName AddressFix
	 * @var oecd\ties\stffatcatypes\v1\AddressFix_Type
	 */
	public $AddressFix;
	/**
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v1
	 * @xmlMinOccurs 0
	 * @xmlName AddressFree
	 * @var string
	 */
	public $AddressFree;
	/**
	 * @xmlType attribute
	 * @xmlName legalAddressType
	 * @var oecd\ties\stf\v4\OECDLegalAddressType_EnumType
	 */
	public $legalAddressType;


} // end class Address_Type
