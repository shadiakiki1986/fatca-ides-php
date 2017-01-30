<?php namespace FatcaXsdPhp;

use oecd\ties\stffatcatypes\v2;
/**
 * @xmlNamespace 
 * @xmlType OrganisationParty_Type
 * @xmlName CorrectableReportOrganisation_Type
 * @var CorrectableReportOrganisation_Type
 * @xmlDefinition Type that contains details about entity that can act as a filer of report ( e.g. Sponsor, Reporting FI or Intermediary)
 */
class CorrectableReportOrganisation_Type
	extends v2\OrganisationParty_Type
	{

	
	/**
	 * @Definition Code that identifies the category of a filer
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v2
	 * @xmlMinOccurs 0
	 * @xmlName FilerCategory
	 * @var oecd\ties\fatca\v2\FatcaFilerCategory_EnumType
	 */
	public $FilerCategory;
	/**
	 * @Definition Document specification for organisation
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v2
	 * @xmlName DocSpec
	 * @var oecd\ties\fatca\v2\DocSpec_Type
	 */
	public $DocSpec;


} // end class CorrectableReportOrganisation_Type
