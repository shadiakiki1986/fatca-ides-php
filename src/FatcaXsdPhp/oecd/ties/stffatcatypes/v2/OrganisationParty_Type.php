<?php
namespace oecd\ties\stffatcatypes\v2;

/**
 * @xmlNamespace urn:oecd:ties:stffatcatypes:v2
 * @xmlType 
 * @xmlName OrganisationParty_Type
 * @var oecd\ties\stffatcatypes\v2\OrganisationParty_Type
 * @xmlDefinition 
This container brings together all data about an organisation as a party. Name and address are required components and each can be present more than once to enable a complete description. Whenever possible one or more identifiers (TIN, etc.) should be added as well as a residence country code. Additional data that describes and identifies the party can be given. The code for the legal type according to the OECD code list must be added. The structures of all of the sub elements are defined elsewhere in this schema.
 */
class OrganisationParty_Type
	{

	
	/**
	 * @Definition 2-character code of tax residence country for the organisation
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v2
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName ResCountryCode
	 * @var oecd\ties\isofatcatypes\v1\CountryCode_Type
	 */
	public $ResCountryCode;
	/**
	 * @Definition Tax Identification Number
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v2
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName TIN
	 * @var oecd\ties\stffatcatypes\v2\TIN_Type
	 */
	public $TIN;
	/**
	 * @Definition Name of the organisation
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v2
	 * @xmlMaxOccurs unbounded
	 * @xmlName Name
	 * @var oecd\ties\stffatcatypes\v2\NameOrganisation_Type
	 */
	public $Name;
	/**
	 * @Definition Address of the organisation
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v2
	 * @xmlMaxOccurs unbounded
	 * @xmlName Address
	 * @var oecd\ties\stffatcatypes\v2\Address_Type
	 */
	public $Address;


} // end class OrganisationParty_Type
