<?php namespace FatcaXsdPhp;

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName DocSpec_Type
 * @var DocSpec_Type
 * @xmlDefinition Document specification: Data identifying and describing the document, where
'document' here means the part of a message that is to transmit the data about a single block of FATCA information. 
 */
class DocSpec_Type
	{

	
	/**
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v1
	 * @xmlName DocTypeIndic
	 * @var oecd\ties\fatca\v1\FatcaDocTypeIndic_EnumType
	 */
	public $DocTypeIndic;
	/**
	 * @Definition Sender's unique identifier of this document 
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v1
	 * @xmlName DocRefId
	 * @var string
	 */
	public $DocRefId;
	/**
	 * @Definition Reference id of the message of the document referred to if this is a correction
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v1
	 * @xmlMinOccurs 0
	 * @xmlName CorrMessageRefId
	 * @var string
	 */
	public $CorrMessageRefId;
	/**
	 * @Definition Reference id of the document referred to if this is correction
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v1
	 * @xmlMinOccurs 0
	 * @xmlName CorrDocRefId
	 * @var string
	 */
	public $CorrDocRefId;


} // end class DocSpec_Type
