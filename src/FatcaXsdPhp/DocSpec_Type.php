<?php namespace FatcaXsdPhp;

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName DocSpec_Type
 * @var DocSpec_Type
 * @xmlDefinition Type for document specification: Data identifying and describing the document, where
'document' here means the part of a message that is to transmit the data about a single block of FATCA information
 */
class DocSpec_Type
	{

	
	/**
	 * @Definition Identifies the type of the document( e.g. New Data, New Test Data, Corrected Data)
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v2
	 * @xmlName DocTypeIndic
	 * @var oecd\ties\fatca\v2\FatcaDocTypeIndic_EnumType
	 */
	public $DocTypeIndic;
	/**
	 * @Definition Sender's unique identifier of this document
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v2
	 * @xmlName DocRefId
	 * @var oecd\ties\stffatcatypes\v2\StringMax200_Type
	 */
	public $DocRefId;
	/**
	 * @Definition Reference id of the message of the document referred to if this is a correction
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v2
	 * @xmlMinOccurs 0
	 * @xmlName CorrMessageRefId
	 * @var oecd\ties\stffatcatypes\v2\StringMax200_Type
	 */
	public $CorrMessageRefId;
	/**
	 * @Definition Reference id of the document referred to if this is correction
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:fatca:v2
	 * @xmlMinOccurs 0
	 * @xmlName CorrDocRefId
	 * @var oecd\ties\stffatcatypes\v2\StringMax200_Type
	 */
	public $CorrDocRefId;


} // end class DocSpec_Type
