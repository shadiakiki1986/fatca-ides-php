<?php
namespace oecd\ties\stffatcatypes\v1;

/**
 * @xmlNamespace urn:oecd:ties:stffatcatypes:v1
 * @xmlType 
 * @xmlName PersonParty_Type
 * @var oecd\ties\stffatcatypes\v1\PersonParty_Type
 * @xmlDefinition 
This container brings together all data about a person as a party. Name and address are required components and each can
be present more than once to enable as complete a description as possible. Whenever possible one or more identifiers (TIN
etc) should be added as well as a residence country code. Additional data that describes and identifies the party can be
given. The code for the legal type according to the OECD codelist must be added. The structures of
all of the subelements are defined elsewhere in this schema.
 */
class PersonParty_Type
	{

	
	/**
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v1
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName ResCountryCode
	 * @var oecd\ties\isofatcatypes\v1\CountryCode_Type
	 */
	public $ResCountryCode;
	/**
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v1
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName TIN
	 * @var oecd\ties\stffatcatypes\v1\TIN_Type
	 */
	public $TIN;
	/**
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v1
	 * @xmlMaxOccurs unbounded
	 * @xmlName Name
	 * @var oecd\ties\stffatcatypes\v1\NamePerson_Type
	 */
	public $Name;
	/**
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v1
	 * @xmlMaxOccurs unbounded
	 * @xmlName Address
	 * @var oecd\ties\stffatcatypes\v1\Address_Type
	 */
	public $Address;
	/**
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v1
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName Nationality
	 * @var oecd\ties\isofatcatypes\v1\CountryCode_Type
	 */
	public $Nationality;
	/**
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v1
	 * @xmlMinOccurs 0
	 * @xmlName BirthInfo
	 */
	public $BirthInfo;


} // end class PersonParty_Type
