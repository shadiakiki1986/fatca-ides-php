<?php namespace FatcaXsdPhp;

use oecd\ties\stffatcatypes\v2;
/**
 * @xmlNamespace 
 * @xmlType StringMax200_Type
 * @xmlName FIAccountNumber_Type
 * @var FIAccountNumber_Type
 * @xmlDefinition Type that contains the details about account number
 */
class FIAccountNumber_Type
	extends v2\StringMax200_Type
	{

	
	/**
	 * @Definition Account number type
	 * @xmlType attribute
	 * @xmlName AcctNumberType
	 * @var oecd\ties\stf\v4\AcctNumberType_EnumType
	 */
	public $AcctNumberType;


} // end class FIAccountNumber_Type
