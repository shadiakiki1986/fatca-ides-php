<?php namespace FatcaXsdPhp;

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName CARRef_Type
 * @var CARRef_Type
 * @xmlDefinition Type for CAR reference. Contains identifying information for Competent Authority Request. Required when account report is sent as a response to a Competent Authority Request (CAR). Used to associate the response to Competent Authority Request to a pooled report.
 */
class CARRef_Type
	{

	
	/**
	 * @Definition GIIN of reporting financial institution in the pool report related to the Competent Authority Request
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v2
	 * @xmlName PoolReportReportingFIGIIN
	 * @var oecd\ties\stffatcatypes\v2\StringMax200_Type
	 */
	public $PoolReportReportingFIGIIN;
	/**
	 * @Definition Reference Id of the message that contains pool report related to the Competent Authority Request
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v2
	 * @xmlName PoolReportMessageRefId
	 * @var oecd\ties\stffatcatypes\v2\StringMax200_Type
	 */
	public $PoolReportMessageRefId;
	/**
	 * @Definition Document reference Id of the pool report related to the Competent Authority Request
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v2
	 * @xmlName PoolReportDocRefId
	 * @var oecd\ties\stffatcatypes\v2\StringMax200_Type
	 */
	public $PoolReportDocRefId;


} // end class CARRef_Type
