<?php
namespace oecd\ties\stffatcatypes\v1;

/**
 * @xmlNamespace urn:oecd:ties:stffatcatypes:v1
 * @xmlType 
 * @xmlName MessageSpec_Type
 * @var oecd\ties\stffatcatypes\v1\MessageSpec_Type
 * @xmlDefinition Information in the message header identifies the Financial Institution (FI) or Tax Administration that is sending the message.  It specifies when the message was created, what calendar year the report is for, and the nature of the report (original, corrected, supplemental, etc).
 */
class MessageSpec_Type
	{

	
	/**
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v1
	 * @xmlMinOccurs 0
	 * @xmlName SendingCompanyIN
	 * @var string
	 */
	public $SendingCompanyIN;
	/**
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v1
	 * @xmlName TransmittingCountry
	 * @var oecd\ties\isofatcatypes\v1\CountryCode_Type
	 */
	public $TransmittingCountry;
	/**
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v1
	 * @xmlName ReceivingCountry
	 * @var oecd\ties\isofatcatypes\v1\CountryCode_Type
	 */
	public $ReceivingCountry;
	/**
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v1
	 * @xmlName MessageType
	 * @var oecd\ties\stffatcatypes\v1\MessageType_EnumType
	 */
	public $MessageType;
	/**
	 * @Definition Free text expressing the restrictions for use of the information this
message contains and the legal framework under which it is given
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v1
	 * @xmlMinOccurs 0
	 * @xmlName Warning
	 * @var string
	 */
	public $Warning;
	/**
	 * @Definition All necessary contact information about persons responsible for and
involved in the processing of the data transmitted in this message, both legally and technically. Free text as this is not
intended for automatic processing. 
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v1
	 * @xmlMinOccurs 0
	 * @xmlName Contact
	 * @var string
	 */
	public $Contact;
	/**
	 * @Definition Sender's unique identifier for this message
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v1
	 * @xmlName MessageRefId
	 * @var string
	 */
	public $MessageRefId;
	/**
	 * @Definition Sender's unique identifier that has to be corrected.  Must point to 1 or more previous message
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v1
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName CorrMessageRefId
	 * @var string
	 */
	public $CorrMessageRefId;
	/**
	 * @Definition The reporting year for which information is transmitted in documents of
the current message.
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v1
	 * @xmlName ReportingPeriod
	 * @var string
	 */
	public $ReportingPeriod;
	/**
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v1
	 * @xmlName Timestamp
	 * @var string
	 */
	public $Timestamp;


} // end class MessageSpec_Type
