<?php namespace FatcaXsdPhp;

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName CorrectableAccountReport_Type
 * @var CorrectableAccountReport_Type
 * @xmlDefinition Type that contains details about account report
 */
class CorrectableAccountReport_Type
	{

	
	/**
	 * @Definition Document specification for the account report
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v2
	 * @xmlName DocSpec
	 * @var oecd\ties\fatca\v2\DocSpec_Type
	 */
	public $DocSpec;
	/**
	 * @Definition Financial institution account number used to uniquely identify the account holder or payee
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v2
	 * @xmlName AccountNumber
	 * @var oecd\ties\fatca\v2\FIAccountNumber_Type
	 */
	public $AccountNumber;
	/**
	 * @Definition The indicator whether the financial institution account status is closed for the account holder or payee
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v2
	 * @xmlMinOccurs 0
	 * @xmlName AccountClosed
	 * @var boolean
	 */
	public $AccountClosed;
	/**
	 * @Definition Detailed information of the entity account holder or payee
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v2
	 * @xmlName AccountHolder
	 * @var oecd\ties\fatca\v2\AccountHolder_Type
	 */
	public $AccountHolder;
	/**
	 * @Definition Detailed information of the substantial owner of the account
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v2
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName SubstantialOwner
	 * @var oecd\ties\fatca\v2\SubstantialOwner_Type
	 */
	public $SubstantialOwner;
	/**
	 * @Definition The account balance or value of the reported financial account
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v2
	 * @xmlName AccountBalance
	 * @var oecd\ties\stffatcatypes\v2\MonAmnt_Type
	 */
	public $AccountBalance;
	/**
	 * @Definition The aggregate gross payment made to the reported financial account or  to an entity that is not an account holder. Payment information is a repeating element, if more than one payment type needs to be reported
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v2
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName Payment
	 * @var oecd\ties\fatca\v2\Payment_Type
	 */
	public $Payment;
	/**
	 * @Definition Contains identifying information for Competent Authority Request (CAR). Required when account report is sent as a response to a Competent Authority Request (CAR). Used to associate the response to Competent Authority Request to a pooled report.
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v2
	 * @xmlMinOccurs 0
	 * @xmlName CARRef
	 * @var oecd\ties\fatca\v2\CARRef_Type
	 */
	public $CARRef;
	/**
	 * @Definition Additional data for the reported account. Can be used for additional information in reciprocal report.
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v2
	 * @xmlMinOccurs 0
	 * @xmlName AdditionalData
	 * @var oecd\ties\fatca\v2\AdditionalData_Type
	 */
	public $AdditionalData;


} // end class CorrectableAccountReport_Type
