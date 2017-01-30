<?php namespace FatcaXsdPhp;

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName SubstantialOwner_Type
 * @var SubstantialOwner_Type
 * @xmlDefinition Type that contains details for a substantial owner of an account
 */
class SubstantialOwner_Type
	{

	
	/**
	 * @Definition Substantial owner of the account as a natural person
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v2
	 * @xmlName Individual
	 * @var oecd\ties\stffatcatypes\v2\PersonParty_Type
	 */
	public $Individual;
	/**
	 * @Definition Substantial owner of the account not as a natural person
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v2
	 * @xmlName Organisation
	 * @var oecd\ties\stffatcatypes\v2\OrganisationParty_Type
	 */
	public $Organisation;


} // end class SubstantialOwner_Type
