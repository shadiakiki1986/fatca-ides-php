<?php
namespace oecd\ties\stffatcatypes\v2;

use oecd\ties\stffatcatypes\v2;
/**
 * @xmlNamespace urn:oecd:ties:stffatcatypes:v2
 * @xmlType StringMax200_Type
 * @xmlName NameOrganisation_Type
 * @var oecd\ties\stffatcatypes\v2\NameOrganisation_Type
 * @xmlDefinition Name of organisation
 */
class NameOrganisation_Type
	extends v2\StringMax200_Type
	{

	
	/**
	 * @Definition Defines the name type of organization name (e.g. legal)
	 * @xmlType attribute
	 * @xmlName nameType
	 * @var oecd\ties\stf\v4\OECDNameType_EnumType
	 */
	public $nameType;


} // end class NameOrganisation_Type
