<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName CorrectableAccountReport_Type
 * @var CorrectableAccountReport_Type
 */
class CorrectableAccountReport_Type
	{

	
	/**
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v1
	 * @xmlName DocSpec
	 * @var oecd\ties\fatca\v1\DocSpec_Type
	 */
	public $DocSpec;
	/**
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v1
	 * @xmlName AccountNumber
	 * @var oecd\ties\fatca\v1\FIAccountNumber_Type
	 */
	public $AccountNumber;
	/**
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v1
	 * @xmlName AccountHolder
	 * @var oecd\ties\fatca\v1\AccountHolder_Type
	 */
	public $AccountHolder;
	/**
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v1
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName SubstantialOwner
	 * @var oecd\ties\stffatcatypes\v1\PersonParty_Type
	 */
	public $SubstantialOwner;
	/**
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v1
	 * @xmlName AccountBalance
	 * @var oecd\ties\stffatcatypes\v1\MonAmnt_Type
	 */
	public $AccountBalance;
	/**
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v1
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName Payment
	 * @var oecd\ties\fatca\v1\Payment_Type
	 */
	public $Payment;


} // end class CorrectableAccountReport_Type
