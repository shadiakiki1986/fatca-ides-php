<?php
namespace oecd\ties\stffatcatypes\v2;

use oecd\ties\stffatcatypes\v2;
/**
 * @xmlNamespace urn:oecd:ties:stffatcatypes:v2
 * @xmlType StringMax200_Type
 * @xmlName NamePerson_Type
 * @var oecd\ties\stffatcatypes\v2\NamePerson_Type
 * @xmlDefinition The user must spread the data about the name of a party over up to six elements. The container element for this will be 'NameFix'. 
 */
class NamePerson_Type
	extends v2\StringMax200_Type
	{

	
	/**
	 * @Definition His Excellency,Estate of the Late ...
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v2
	 * @xmlMinOccurs 0
	 * @xmlName PrecedingTitle
	 * @var oecd\ties\stffatcatypes\v2\StringMax200_Type
	 */
	public $PrecedingTitle;
	/**
	 * @Definition Greeting title. Example: Mr, Dr, Ms, Herr, etc. Can have multiple titles.
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v2
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName Title
	 * @var oecd\ties\stffatcatypes\v2\StringMax200_Type
	 */
	public $Title;
	/**
	 * @Definition First name of the person
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v2
	 * @xmlName FirstName
	 */
	public $FirstName;
	/**
	 * @Definition Middle name (essential part of the name for many nationalities). Example: Sakthi in "Nivetha Sakthi Shantha". May have multiple middle names.
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v2
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName MiddleName
	 */
	public $MiddleName;
	/**
	 * @Definition de, van, van de, von, etc. Example: Derick de Clarke
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v2
	 * @xmlMinOccurs 0
	 * @xmlName NamePrefix
	 */
	public $NamePrefix;
	/**
	 * @Definition Represents the position of the name in a name string. Can be given name, forename, Christian name, surname, family name, etc. Use the attribute NameType to define the type of name, such as a  company name. 
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v2
	 * @xmlName LastName
	 */
	public $LastName;
	/**
	 * @Definition Jnr, Thr Third, III
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v2
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName GenerationIdentifier
	 * @var oecd\ties\stffatcatypes\v2\StringMax200_Type
	 */
	public $GenerationIdentifier;
	/**
	 * @Definition Could be compressed initials - PhD, VC, QC
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v2
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName Suffix
	 * @var oecd\ties\stffatcatypes\v2\StringMax200_Type
	 */
	public $Suffix;
	/**
	 * @Definition Deceased, Retired ...
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v2
	 * @xmlMinOccurs 0
	 * @xmlName GeneralSuffix
	 * @var oecd\ties\stffatcatypes\v2\StringMax200_Type
	 */
	public $GeneralSuffix;
	/**
	 * @Definition Defines the name type of Person Name. Example:at birth
	 * @xmlType attribute
	 * @xmlName nameType
	 * @var oecd\ties\stf\v4\OECDNameType_EnumType
	 */
	public $nameType;


} // end class NamePerson_Type
