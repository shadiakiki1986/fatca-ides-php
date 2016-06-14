<?php
namespace oecd\ties\stffatcatypes\v1;

/**
 * @xmlNamespace urn:oecd:ties:stffatcatypes:v1
 * @xmlType 
 * @xmlName NamePerson_Type
 * @var oecd\ties\stffatcatypes\v1\NamePerson_Type
 * @xmlDefinition The user must spread the data about the name of a party over up to six elements. The container element for this will be 'NameFix'. 
 */
class NamePerson_Type
	{

	
	/**
	 * @Definition His Excellency,Estate of the Late ...
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v1
	 * @xmlMinOccurs 0
	 * @xmlName PrecedingTitle
	 * @var string
	 */
	public $PrecedingTitle;
	/**
	 * @Definition Greeting title. Example: Mr, Dr, Ms, Herr, etc. Can have multiple titles.
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v1
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName Title
	 * @var string
	 */
	public $Title;
	/**
	 * @Definition FirstName of the person
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v1
	 * @xmlName FirstName
	 */
	public $FirstName;
	/**
	 * @Definition Middle name (essential part of the name for many nationalities). Example: Sakthi in "Nivetha Sakthi Shantha". Can have multiple middle names.
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v1
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName MiddleName
	 */
	public $MiddleName;
	/**
	 * @Definition de, van, van de, von, etc. Example: Derick de Clarke
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v1
	 * @xmlMinOccurs 0
	 * @xmlName NamePrefix
	 */
	public $NamePrefix;
	/**
	 * @Definition Represents the position of the name in a name string. Can be Given Name, Forename, Christian Name, Surname, Family Name, etc. Use the attribute "NameType" to define what type this name is.
In case of a company, this field can be used for the company name.
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v1
	 * @xmlName LastName
	 */
	public $LastName;
	/**
	 * @Definition Jnr, Thr Third, III
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v1
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName GenerationIdentifier
	 * @var string
	 */
	public $GenerationIdentifier;
	/**
	 * @Definition Could be compressed initials - PhD, VC, QC
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v1
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName Suffix
	 * @var string
	 */
	public $Suffix;
	/**
	 * @Definition Deceased, Retired ...
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v1
	 * @xmlMinOccurs 0
	 * @xmlName GeneralSuffix
	 * @var string
	 */
	public $GeneralSuffix;
	/**
	 * @xmlType attribute
	 * @xmlName nameType
	 * @var oecd\ties\stf\v4\OECDNameType_EnumType
	 */
	public $nameType;


} // end class NamePerson_Type
