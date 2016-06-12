<?php namespace FatcaXsdPhp;

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName Fatca_Type
 * @var Fatca_Type
 */
class Fatca_Type
	{

	
	/**
	 * @Definition Reporting financial institution
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v1
	 * @xmlName ReportingFI
	 * @var oecd\ties\fatca\v1\CorrectableOrganisationParty_Type
	 */
	public $ReportingFI;
	/**
	 * @Definition Group that wraps the details for a financial institution FATCA report
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v1
	 * @xmlMaxOccurs unbounded
	 * @xmlName ReportingGroup
	 * @var oecd\ties\fatca\v1\ReportingGroup
	 */
	public $ReportingGroup;


} // end class Fatca_Type
