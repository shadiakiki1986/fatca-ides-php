<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName Payment_Type
 * @var Payment_Type
 */
class Payment_Type
	{

	
	/**
	 * @Definition Type of payment (interest, dividend,...)
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v1
	 * @xmlName Type
	 * @var oecd\ties\fatca\v1\FatcaPaymentType_EnumType
	 */
	public $Type;
	/**
	 * @Definition The amount of payment
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v1
	 * @xmlName PaymentAmnt
	 * @var oecd\ties\stffatcatypes\v1\MonAmnt_Type
	 */
	public $PaymentAmnt;


} // end class Payment_Type
