<?php
namespace oecd\ties\stffatcatypes\v2;

/**
 * @xmlNamespace urn:oecd:ties:stffatcatypes:v2
 * @xmlType decimal
 * @xmlName TwoDigFract_Type
 * @var oecd\ties\stffatcatypes\v2\TwoDigFract_Type
 * @xmlDefinition Data type for any kind of numeric data with two decimal fraction digits, especially monetary amounts
 */
class TwoDigFract_Type
	{

		/**
		 * @xmlType value
		 * @var float
		 */
		public $value;

} // end class TwoDigFract_Type
