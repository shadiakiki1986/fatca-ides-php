<?php
namespace oecd\ties\stffatcatypes\v1;

/**
 * @xmlNamespace urn:oecd:ties:stffatcatypes:v1
 * @xmlType string
 * @xmlName NameOrganisation_Type
 * @var oecd\ties\stffatcatypes\v1\NameOrganisation_Type
 * @xmlDefinition Name of organisation
 */
class NameOrganisation_Type
	{

		/**
		 * @xmlType value
		 * @var string
		 */
		public $value;	
	/**
	 * @xmlType attribute
	 * @xmlName nameType
	 * @var oecd\ties\stf\v4\OECDNameType_EnumType
	 */
	public $nameType;


} // end class NameOrganisation_Type
