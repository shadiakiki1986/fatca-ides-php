<?php namespace FatcaXsdPhp;

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName AdditionalData_Type
 * @var AdditionalData_Type
 * @xmlDefinition The type for providing additional data. Contains elements of information identified by a descriptive name
 */
class AdditionalData_Type
	{

	
	/**
	 * @Definition Element of additional data item. Consists of identifying name and content
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v2
	 * @xmlMinOccurs 1
	 * @xmlMaxOccurs 100
	 * @xmlName AdditionalItem
	 */
	public $AdditionalItem;


} // end class AdditionalData_Type
