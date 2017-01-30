<?php
namespace oecd\ties\stffatcatypes\v2;

/**
 * @xmlNamespace urn:oecd:ties:stffatcatypes:v2
 * @xmlType 
 * @xmlName PersonParty_Type
 * @var oecd\ties\stffatcatypes\v2\PersonParty_Type
 * @xmlDefinition This container brings together all data about a person as a party. Name and address are required components and each can be present more than once to enable as complete a description as possible. Whenever possible one or more identifiers (TIN etc) should be added as well as a residence country code. Additional data that describes and identifies the party can be given. The code for the legal type according to the OECD code list must be added. The structures of all of the sub elements are defined elsewhere in this schema.
 */
class PersonParty_Type
	{

	
	/**
	 * @Definition 2-character code of tax residence country for the person
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v2
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName ResCountryCode
	 * @var oecd\ties\isofatcatypes\v1\CountryCode_Type
	 */
	public $ResCountryCode;
	/**
	 * @Definition Tax Identification Number (TIN) used by the receiving tax administration to identify the person
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v2
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName TIN
	 * @var oecd\ties\stffatcatypes\v2\TIN_Type
	 */
	public $TIN;
	/**
	 * @Definition Name of the person
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v2
	 * @xmlMaxOccurs unbounded
	 * @xmlName Name
	 * @var oecd\ties\stffatcatypes\v2\NamePerson_Type
	 */
	public $Name;
	/**
	 * @Definition Address of the person
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v2
	 * @xmlMaxOccurs unbounded
	 * @xmlName Address
	 * @var oecd\ties\stffatcatypes\v2\Address_Type
	 */
	public $Address;
	/**
	 * @Definition 2-character code of nationality of the person
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v2
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName Nationality
	 * @var oecd\ties\isofatcatypes\v1\CountryCode_Type
	 */
	public $Nationality;
	/**
	 * @Definition Birth information about the person
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v2
	 * @xmlMinOccurs 0
	 * @xmlName BirthInfo
	 */
	public $BirthInfo;


} // end class PersonParty_Type
