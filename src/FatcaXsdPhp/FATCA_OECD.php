<?php namespace FatcaXsdPhp;

/**
 * @xmlNamespace urn:oecd:ties:fatca:v1
 * @xmlType 
 * @xmlName FATCA_OECD
 * @var FATCA_OECD
 */
class FATCA_OECD
	{

	
	/**
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v1
	 * @xmlName MessageSpec
	 * @var oecd\ties\stffatcatypes\v1\MessageSpec_Type
	 */
	public $MessageSpec;
	/**
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v1
	 * @xmlMaxOccurs unbounded
	 * @xmlName FATCA
	 * @var oecd\ties\fatca\v1\Fatca_Type
	 */
	public $FATCA;
	/**
	 * @Definition FATCA Version 
	 * @xmlType attribute
	 * @xmlName version
	 * @var string
	 */
	public $version;


} // end class FATCA_OECD
