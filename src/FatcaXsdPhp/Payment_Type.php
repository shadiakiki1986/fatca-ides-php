<?php namespace FatcaXsdPhp;

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName Payment_Type
 * @var Payment_Type
 * @xmlDefinition Type that contains details about payment type
 */
class Payment_Type
	{

	
	/**
	 * @Definition Type of payment (interest, dividend, etc.)
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v2
	 * @xmlName Type
	 * @var oecd\ties\fatca\v2\FatcaPaymentType_EnumType
	 */
	public $Type;
	/**
	 * @Definition Additional information about the payment type
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v2
	 * @xmlMinOccurs 0
	 * @xmlName PaymentTypeDesc
	 * @var oecd\ties\stffatcatypes\v2\StringMax4000_Type
	 */
	public $PaymentTypeDesc;
	/**
	 * @Definition The amount of payment
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v2
	 * @xmlName PaymentAmnt
	 * @var oecd\ties\stffatcatypes\v2\MonAmnt_Type
	 */
	public $PaymentAmnt;


} // end class Payment_Type
