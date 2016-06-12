<?php
namespace oecd\ties\stffatcatypes\v1;

/**
 * @xmlNamespace urn:oecd:ties:stffatcatypes:v1
 * @xmlType 
 * @xmlName AddressFix_Type
 * @var oecd\ties\stffatcatypes\v1\AddressFix_Type
 * @xmlDefinition 
			Structure of the address for a party broken down into its logical parts, recommended for easy matching. The 'City' element is the only required subelement. All of the subelements are simple text - data type 'string'.
			
 */
class AddressFix_Type
	{

	
	/**
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v1
	 * @xmlMinOccurs 0
	 * @xmlName Street
	 * @var string
	 */
	public $Street;
	/**
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v1
	 * @xmlMinOccurs 0
	 * @xmlName BuildingIdentifier
	 * @var string
	 */
	public $BuildingIdentifier;
	/**
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v1
	 * @xmlMinOccurs 0
	 * @xmlName SuiteIdentifier
	 * @var string
	 */
	public $SuiteIdentifier;
	/**
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v1
	 * @xmlMinOccurs 0
	 * @xmlName FloorIdentifier
	 * @var string
	 */
	public $FloorIdentifier;
	/**
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v1
	 * @xmlMinOccurs 0
	 * @xmlName DistrictName
	 * @var string
	 */
	public $DistrictName;
	/**
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v1
	 * @xmlMinOccurs 0
	 * @xmlName POB
	 * @var string
	 */
	public $POB;
	/**
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v1
	 * @xmlMinOccurs 0
	 * @xmlName PostCode
	 * @var string
	 */
	public $PostCode;
	/**
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v1
	 * @xmlName City
	 * @var string
	 */
	public $City;
	/**
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v1
	 * @xmlMinOccurs 0
	 * @xmlName CountrySubentity
	 * @var string
	 */
	public $CountrySubentity;


} // end class AddressFix_Type
