<?php

use oecd\ties\stffatcatypes\v1;
/**
 * @xmlNamespace 
 * @xmlType OrganisationParty_Type
 * @xmlName CorrectableOrganisationParty_Type
 * @var CorrectableOrganisationParty_Type
 */
class CorrectableOrganisationParty_Type
	extends v1\OrganisationParty_Type
	{

	
	/**
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v1
	 * @xmlName DocSpec
	 * @var oecd\ties\fatca\v1\DocSpec_Type
	 */
	public $DocSpec;


} // end class CorrectableOrganisationParty_Type
