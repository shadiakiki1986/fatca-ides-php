<?php
namespace oecd\ties\stffatcatypes\v2;

use oecd\ties\stffatcatypes\v2;
/**
 * @xmlNamespace urn:oecd:ties:stffatcatypes:v2
 * @xmlType TwoDigFract_Type
 * @xmlName MonAmnt_Type
 * @var oecd\ties\stffatcatypes\v2\MonAmnt_Type
 * @xmlDefinition 
This data type is to be used whenever monetary amounts are communicated. Such amounts are entered with 
2-digit fractions of the main currency unit, e.g. 50500.00. The currency code is based on ISO 4217 and included in attribute currCode.

 */
class MonAmnt_Type
	extends v2\TwoDigFract_Type
	{

	
	/**
	 * @Definition 3-letter currency code specified by ISO 4217 standard
	 * @xmlType attribute
	 * @xmlName currCode
	 * @var oecd\ties\isofatcatypes\v1\currCode_Type
	 */
	public $currCode;


} // end class MonAmnt_Type
