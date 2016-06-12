<?php namespace FatcaXsdPhp;

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName CorrectablePoolReport_Type
 * @var CorrectablePoolReport_Type
 */
class CorrectablePoolReport_Type
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
	 * @xmlName AccountCount
	 * @var integer
	 */
	public $AccountCount;
	/**
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v1
	 * @xmlName AccountPoolReportType
	 * @var oecd\ties\fatca\v1\FatcaAcctPoolReportType_EnumType
	 */
	public $AccountPoolReportType;
	/**
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v1
	 * @xmlName PoolBalance
	 * @var oecd\ties\stffatcatypes\v1\MonAmnt_Type
	 */
	public $PoolBalance;


} // end class CorrectablePoolReport_Type
