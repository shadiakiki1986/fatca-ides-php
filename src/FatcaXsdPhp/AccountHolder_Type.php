<?php namespace FatcaXsdPhp;

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName AccountHolder_Type
 * @var AccountHolder_Type
 */
class AccountHolder_Type
	{

	
	/**
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v1
	 * @xmlName Individual
	 * @var oecd\ties\stffatcatypes\v1\PersonParty_Type
	 */
	public $Individual;
	/**
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v1
	 * @xmlName Organisation
	 * @var oecd\ties\stffatcatypes\v1\OrganisationParty_Type
	 */
	public $Organisation;
	/**
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v1
	 * @xmlName AcctHolderType
	 * @var oecd\ties\fatca\v1\FatcaAcctHolderType_EnumType
	 */
	public $AcctHolderType;


} // end class AccountHolder_Type
