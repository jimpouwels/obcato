<?php

	// No direct access
	defined('_ACCESS') or die;
	
	include_once FRONTEND_REQUEST . "libraries/utilities/string_utility.php";
	
	class FormHandler {
		
		/*
			Private constructor.
		*/
		private function __construct() {
		}
		
		/*
			Returns the value of the given parameter in the POST request.
			
			@param $field_name The field name to check
		*/
		public static function getFieldValue($field_name) {
			$value = null;
			if (isset($_POST[$field_name])) {
				$value = $_POST[$field_name];
				if (!is_array($_POST[$field_name])) {
					$value = StringUtility::unescapeXml($value);
					$value = str_replace("'", "\\'", $value);
				}
			}
			return $value;
		}
		
	}
	
?>