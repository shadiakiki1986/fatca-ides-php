<?php namespace FatcaXsdPhp;

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName CorrectablePoolReport_Type
 * @var CorrectablePoolReport_Type
 * @xmlDefinition Type that contains details about the pool report
 */
class CorrectablePoolReport_Type
	{

	
	/**
	 * @Definition Document specification for the pool report
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v2
	 * @xmlName DocSpec
	 * @var oecd\ties\fatca\v2\DocSpec_Type
	 */
	public $DocSpec;
	/**
	 * @Definition The number of accounts associated with the pool report
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v2
	 * @xmlName AccountCount
	 * @var integer
	 */
	public $AccountCount;
	/**
	 * @Definition The pool report account status for the account holders or payees
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v2
	 * @xmlName AccountPoolReportType
	 * @var oecd\ties\fatca\v2\FatcaAcctPoolReportType_EnumType
	 */
	public $AccountPoolReportType;
	/**
	 * @Definition For non-participating FFIs pool types, the pool balance is the aggregate foreign reportable amounts paid to non-participating FFIs within the reported pool. For all other pool types, the pool balance is the aggregate amount or value of all accounts within the reported pool.
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v2
	 * @xmlName PoolBalance
	 * @var oecd\ties\stffatcatypes\v2\MonAmnt_Type
	 */
	public $PoolBalance;


} // end class CorrectablePoolReport_Type
