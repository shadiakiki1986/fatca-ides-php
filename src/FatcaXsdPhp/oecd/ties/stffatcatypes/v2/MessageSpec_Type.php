<?php
namespace oecd\ties\stffatcatypes\v2;

/**
 * @xmlNamespace urn:oecd:ties:stffatcatypes:v2
 * @xmlType 
 * @xmlName MessageSpec_Type
 * @var oecd\ties\stffatcatypes\v2\MessageSpec_Type
 * @xmlDefinition Type for message specification. Identifies the Financial Institution (FI), or Tax Administration or third-party vendor sending a message.  It specifies the date created, reporting year, and the nature of the report (original, corrected, amended, etc.).
 */
class MessageSpec_Type
	{

	
	/**
	 * @Definition Identifying number of the sender. For example, if the sender is a financial institution, the SendingCompanyIN will be the unique 19-character GIIN of this institution. If the sender is HCTA, the SendingCompanyIN will be the unique 19-character FATCT entity identifier of the HCTA.
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v2
	 * @xmlMinOccurs 0
	 * @xmlName SendingCompanyIN
	 * @var oecd\ties\stffatcatypes\v2\StringMax200_Type
	 */
	public $SendingCompanyIN;
	/**
	 * @Definition The jurisdiction where the reported financial account is maintained or where the reported payment is made by the reporting FI. If HCTA, the country is the tax jurisdiction and uses alpha-2 country code specified by ISO 3166-1 standard.
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v2
	 * @xmlName TransmittingCountry
	 * @var oecd\ties\isofatcatypes\v1\CountryCode_Type
	 */
	public $TransmittingCountry;
	/**
	 * @Definition Tax jurisdiction of the recipient, e.g. the United States (US) in alpha-2 country code specified by ISO 3166-1 standard.
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v2
	 * @xmlName ReceivingCountry
	 * @var oecd\ties\isofatcatypes\v1\CountryCode_Type
	 */
	public $ReceivingCountry;
	/**
	 * @Definition The type of message, e.g. FATCA
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v2
	 * @xmlName MessageType
	 * @var oecd\ties\stffatcatypes\v2\MessageType_EnumType
	 */
	public $MessageType;
	/**
	 * @Definition Free text expressing the restrictions for use of the information this
message contains and the legal framework under which it is given
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v2
	 * @xmlMinOccurs 0
	 * @xmlName Warning
	 * @var oecd\ties\stffatcatypes\v2\StringMax4000_Type
	 */
	public $Warning;
	/**
	 * @Definition Free text field not intended for automatic processing.  May contain contact information about persons responsible for legal and/or technical data preparation and transmission.
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v2
	 * @xmlMinOccurs 0
	 * @xmlName Contact
	 * @var oecd\ties\stffatcatypes\v2\StringMax200_Type
	 */
	public $Contact;
	/**
	 * @Definition Sender's unique identifier for this message. Must be unique for the lifespan of the FATCA reporting system.
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v2
	 * @xmlName MessageRefId
	 * @var oecd\ties\stffatcatypes\v2\StringMax200_Type
	 */
	public $MessageRefId;
	/**
	 * @Definition Sender's unique identifier for a previously filed message to be corrected, voided or amended. May reference one or more previous messages.
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v2
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName CorrMessageRefId
	 * @var oecd\ties\stffatcatypes\v2\StringMax200_Type
	 */
	public $CorrMessageRefId;
	/**
	 * @Definition The reporting year for the current message
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v2
	 * @xmlName ReportingPeriod
	 * @var string
	 */
	public $ReportingPeriod;
	/**
	 * @Definition The date and time when the report was created
	 * @xmlType element
	 * @xmlNamespace urn:oecd:ties:stffatcatypes:v2
	 * @xmlName Timestamp
	 * @var string
	 */
	public $Timestamp;


} // end class MessageSpec_Type
