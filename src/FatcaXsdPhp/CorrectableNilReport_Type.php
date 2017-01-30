<?php namespace FatcaXsdPhp;

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName CorrectableNilReport_Type
 * @var CorrectableNilReport_Type
 * @xmlDefinition Type that contains details about Nil Report. Nil Report indicates that financial institution does not have accounts to report.
 */
class CorrectableNilReport_Type
	{

	
	/**
	 * @Definition Document specification for the Nil Report
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v2
	 * @xmlName DocSpec
	 * @var oecd\ties\fatca\v2\DocSpec_Type
	 */
	public $DocSpec;
	/**
	 * @Definition Indicator that shows that financial institution doesn't have accounts to report
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v2
	 * @xmlName NoAccountToReport
	 * @var string
	 */
	public $NoAccountToReport;


} // end class CorrectableNilReport_Type
