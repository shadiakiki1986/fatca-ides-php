<?php namespace FatcaXsdPhp;

use oecd\ties\stffatcatypes\v2;
/**
 * @xmlNamespace 
 * @xmlType OrganisationParty_Type
 * @xmlName CorrectableOrganisationParty_Type
 * @var CorrectableOrganisationParty_Type
 */
class CorrectableOrganisationParty_Type
	extends v2\OrganisationParty_Type
	{

	
	/**
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v2
	 * @xmlName DocSpec
	 * @var oecd\ties\fatca\v2\DocSpec_Type
	 */
	public $DocSpec;


} // end class CorrectableOrganisationParty_Type
