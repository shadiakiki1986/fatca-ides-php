<?php
namespace oecd\ties\stffatcatypes\v2;

use oecd\ties\stffatcatypes\v2;
/**
 * @xmlNamespace urn:oecd:ties:stffatcatypes:v2
 * @xmlType StringMin1Max200_Type
 * @xmlName TIN_Type
 * @var oecd\ties\stffatcatypes\v2\TIN_Type
 * @xmlDefinition This is the identification number/identification code for the party. As the identifier may be not strictly numeric, it is just defined as a string of characters. Attribute 'issuedBy' is required to designate the issuer of the identifier. 
 */
class TIN_Type
	extends v2\StringMin1Max200_Type
	{

	
	/**
	 * @Definition Country code of issuing country, indicating country of Residence (to taxes and other)
	 * @xmlType attribute
	 * @xmlName issuedBy
	 * @var oecd\ties\isofatcatypes\v1\CountryCode_Type
	 */
	public $issuedBy;


} // end class TIN_Type
