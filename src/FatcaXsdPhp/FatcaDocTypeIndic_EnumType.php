<?php

/**
 * @xmlNamespace 
 * @xmlType string
 * @xmlName FatcaDocTypeIndic_EnumType
 * @var FatcaDocTypeIndic_EnumType
 * @xmlDefinition The element applies only to the document part in which it is included. In the case of repeated or corrected data elements CorrMessageRefId and CorrDocRefId must contain the identifiers MessageRefId and DocRefId respectively for the data referred to. In the case of a correction the unchanged elements shall be transmitted again - except for the element DocRefId.
			
 */
class FatcaDocTypeIndic_EnumType
	{

		/**
		 * @xmlType value
		 * @var string
		 */
		public $value;

} // end class FatcaDocTypeIndic_EnumType
