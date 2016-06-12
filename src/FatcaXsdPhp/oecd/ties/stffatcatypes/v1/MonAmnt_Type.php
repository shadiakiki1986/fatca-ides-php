<?php
namespace oecd\ties\stffatcatypes\v1;

use oecd\ties\stffatcatypes\v1;
/**
 * @xmlNamespace urn:oecd:ties:stffatcatypes:v1
 * @xmlType TwoDigFract_Type
 * @xmlName MonAmnt_Type
 * @var oecd\ties\stffatcatypes\v1\MonAmnt_Type
 * @xmlDefinition 
This data type is to be used whenever monetary amounts are to be communicated. Such amounts shall be given
including two fractional digits of the main currency unit. The code for the currency in which the value is expressed has to be
taken from the ISO codelist 4217 and added in attribute currCode.

 */
class MonAmnt_Type
	extends v1\TwoDigFract_Type
	{

	
	/**
	 * @xmlType attribute
	 * @xmlName currCode
	 * @var oecd\ties\isofatcatypes\v1\currCode_Type
	 */
	public $currCode;


} // end class MonAmnt_Type
