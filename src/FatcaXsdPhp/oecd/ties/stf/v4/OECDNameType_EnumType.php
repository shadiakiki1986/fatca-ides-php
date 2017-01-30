<?php
namespace oecd\ties\stf\v4;

/**
 * @xmlNamespace urn:oecd:ties:stf:v4
 * @xmlType string
 * @xmlName OECDNameType_EnumType
 * @var oecd\ties\stf\v4\OECDNameType_EnumType
 * @xmlDefinition It is possible for stf documents to contain several names for the same party. This is a qualifier to indicate the type of a particular name. Such types include nicknames ('nick'), names under which a party does business ('dba' doing business as or a name that is used for public acquaintance instead of the official business name). Examples: in the United States, DaimlerChrysler is still known simply as Chrysler, Dr. William Black dba Quality Pediatrics, Inc. 'SMFAliasOrOther' should be chosen if the document is generated from a legacy SMF record, where no further distinction is possible.
				Possible values are:
				OECD201=SMFAliasOrOther
				OECD202=indiv
				OECD203=alias
				OECD204=nick
				OECD205=aka
				OECD206=dba
				OECD207=legal
				OECD208=at birth
			
 */
class OECDNameType_EnumType
	{

		/**
		 * @xmlType value
		 * @var string
		 */
		public $value;

} // end class OECDNameType_EnumType
