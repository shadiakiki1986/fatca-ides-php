<?php namespace FatcaXsdPhp;

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName AccountHolder_Type
 * @var AccountHolder_Type
 * @xmlDefinition Type that contains the details abouts account holder
 */
class AccountHolder_Type
	{

	
	/**
	 * @Definition The entity account holder or payee as a natural person
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v2
	 * @xmlName Individual
	 * @var oecd\ties\stffatcatypes\v2\PersonParty_Type
	 */
	public $Individual;
	/**
	 * @Definition The entity account holder or payee not as a natural person
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v2
	 * @xmlName Organisation
	 * @var oecd\ties\stffatcatypes\v2\OrganisationParty_Type
	 */
	public $Organisation;
	/**
	 * @Definition The entity account holder or payee category
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v2
	 * @xmlName AcctHolderType
	 * @var oecd\ties\fatca\v2\FatcaAcctHolderType_EnumType
	 */
	public $AcctHolderType;


} // end class AccountHolder_Type
