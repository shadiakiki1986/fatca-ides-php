<?php namespace FatcaXsdPhp;

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName ReportingGroup
 * @var ReportingGroup
 * @xmlDefinition .... 
 */
class ReportingGroup
	{

	
	/**
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v1
	 * @xmlName Sponsor
	 * @xmlMinOccurs 0
	 * @var oecd\ties\fatca\v1\CorrectableOrganisationParty_Type
	 */
	public $Sponsor;
	/**
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v1
	 * @xmlName Intermediary
	 * @xmlMinOccurs 0
	 * @var oecd\ties\fatca\v1\CorrectableOrganisationParty_Type
	 */
	public $Intermediary;
	/**
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v1
	 * @xmlName AccountReport
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @var oecd\ties\fatca\v1\CorrectableAccountReport_Type
	 */
	public $AccountReport;
	/**
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v1
	 * @xmlName PoolReport
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @var oecd\ties\fatca\v1\CorrectablePoolReport_Type
	 */
	public $PoolReport;


} // end class ReportingGroup
