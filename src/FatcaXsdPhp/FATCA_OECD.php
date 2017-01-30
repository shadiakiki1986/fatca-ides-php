<?php namespace FatcaXsdPhp;

/**
 * @xmlNamespace urn:oecd:ties:fatca:v2
 * @xmlType 
 * @xmlName FATCA_OECD
 * @var FATCA_OECD
 * @xmlDefinition Root element of XML FATCA Report
 */
class FATCA_OECD
	{

	
	/**
	 * @Definition Message specification section for FATCA Report. Contains identifying information for FATCA Report.
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v2
	 * @xmlName MessageSpec
	 * @var oecd\ties\stffatcatypes\v2\MessageSpec_Type
	 */
	public $MessageSpec;
	/**
	 * @Definition Message body section for a FATCA Report. Contains details for FATCA Report.
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v2
	 * @xmlMaxOccurs unbounded
	 * @xmlName FATCA
	 * @var oecd\ties\fatca\v2\Fatca_Type
	 */
	public $FATCA;
	/**
	 * @Definition FATCA Report Version. The same as FATCA schema version
	 * @xmlType attribute
	 * @xmlName version
	 * @var oecd\ties\stffatcatypes\v2\StringMax10_Type
	 */
	public $version;


} // end class FATCA_OECD
