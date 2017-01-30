<?php
namespace oecd\ties\stffatcatypes\v2;

/**
 * @xmlNamespace urn:oecd:ties:stffatcatypes:v2
 * @xmlType 
 * @xmlName AddressFix_Type
 * @var oecd\ties\stffatcatypes\v2\AddressFix_Type
 * @xmlDefinition Structure of the address for a party in logical parts. Recommended format. The 'City' element is required. All other sub elements are simple text with 'string' data type.
 */
class AddressFix_Type
	{

	
	/**
	 * @Definition Street name
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v2
	 * @xmlMinOccurs 0
	 * @xmlName Street
	 * @var oecd\ties\stffatcatypes\v2\StringMax200_Type
	 */
	public $Street;
	/**
	 * @Definition Building identifier
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v2
	 * @xmlMinOccurs 0
	 * @xmlName BuildingIdentifier
	 * @var oecd\ties\stffatcatypes\v2\StringMax200_Type
	 */
	public $BuildingIdentifier;
	/**
	 * @Definition Suite identifier
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v2
	 * @xmlMinOccurs 0
	 * @xmlName SuiteIdentifier
	 * @var oecd\ties\stffatcatypes\v2\StringMax200_Type
	 */
	public $SuiteIdentifier;
	/**
	 * @Definition Floor identifier
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v2
	 * @xmlMinOccurs 0
	 * @xmlName FloorIdentifier
	 * @var oecd\ties\stffatcatypes\v2\StringMax200_Type
	 */
	public $FloorIdentifier;
	/**
	 * @Definition District name
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v2
	 * @xmlMinOccurs 0
	 * @xmlName DistrictName
	 * @var oecd\ties\stffatcatypes\v2\StringMax200_Type
	 */
	public $DistrictName;
	/**
	 * @Definition Post office box
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v2
	 * @xmlMinOccurs 0
	 * @xmlName POB
	 * @var oecd\ties\stffatcatypes\v2\StringMax200_Type
	 */
	public $POB;
	/**
	 * @Definition Postal code
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v2
	 * @xmlMinOccurs 0
	 * @xmlName PostCode
	 * @var oecd\ties\stffatcatypes\v2\StringMax200_Type
	 */
	public $PostCode;
	/**
	 * @Definition City name
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v2
	 * @xmlName City
	 * @var oecd\ties\stffatcatypes\v2\StringMax200_Type
	 */
	public $City;
	/**
	 * @Definition Country sub entity
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v2
	 * @xmlMinOccurs 0
	 * @xmlName CountrySubentity
	 * @var oecd\ties\stffatcatypes\v2\StringMax200_Type
	 */
	public $CountrySubentity;


} // end class AddressFix_Type
