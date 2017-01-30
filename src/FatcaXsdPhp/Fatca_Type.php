<?php namespace FatcaXsdPhp;

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName Fatca_Type
 * @var Fatca_Type
 * @xmlDefinition Type that contains details about FATCA report
 */
class Fatca_Type
	{

	
	/**
	 * @Definition Reporting financial institution
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v2
	 * @xmlName ReportingFI
	 * @var oecd\ties\fatca\v2\CorrectableReportOrganisation_Type
	 */
	public $ReportingFI;
	/**
	 * @Definition Group that wraps the details for a financial institution FATCA report
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v2
	 * @xmlMaxOccurs unbounded
	 * @xmlName ReportingGroup
	 */
	public $ReportingGroup;


} // end class Fatca_Type
