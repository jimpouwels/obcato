<?php

	// No direct access
	defined('_ACCESS') or die;
	
	include_once CMS_ROOT . "libraries/handlers/form_handler.php";
	
	class FormValidator {
		
		/*
			Private constructor.
		*/
		private function __construct() {
		}
	
		/*
			Checks if the given post field is empty
			
			@param $field_name The field name to check
			@param $error_message The error message to show if empty
		*/
		public static function checkEmpty($field_name, $error_message) {
			$value = FormHandler::getFieldValue($field_name);
			if (is_null($value) || $value == '') {
				self::raiseError($field_name, $error_message);
			}
			return $value;
		}
		
		/*
			Checks if the given email address is valid.
			
			@param $field_name The field name to check
			@param $empty_allowed Allowed to be empty
			@param $error_message The error message to show in case of invalid value
		*/
		public static function checkEmailAddress($field_name, $empty_allowed, $error_message) {
			$value = FormHandler::getFieldValue($field_name);
			$valid_email = preg_match("/^[A-Z0-9._%-]+@[A-Z0-9][A-Z0-9.-]{0,61}[A-Z0-9]\.[A-Z]{2,6}$/i", $value);
			if ((!$valid_email && $value == '' && !$empty_allowed) || (!$valid_email && $value != '')) {
				self::raiseError($field_name, $error_message);
			}
			return $value;
		}
		
		/*
			Checks if the given value is a number.
			
			@param $field_name The field name to check
			@param $empty_allowed Allowed to be empty
			@param $error_message The error message to show in case of invalid value
		*/
		public static function checkNumber($field_name, $empty_allowed, $error_message) {
			$value = FormHandler::getFieldValue($field_name);
			$valid_number = is_numeric($value);
			if ((!$valid_number && $value == '' && !$empty_allowed) || (!$valid_number && $value != '')) {
				self::raiseError($field_name, $error_message);
			}
			return $value;
		}
		
		/*
			Checks if the given date is valid.
			
			@param $field_name The field name to check
			@param $empty_allowed Allowed to be empty
			@param $error_message The error message to show in case of invalid value
		*/
		public static function checkDate($field_name, $mandatory, $error_message) {
			$value = FormHandler::getFieldValue($field_name);
			$valid_date = preg_match("/^[0-3]?[0-9]\-[01]?[0-9]\-[12][90][0-9][0-9]$/", $value);
			if ((!$valid_date && $value == '' && $mandatory) || (!$valid_date && $value != '')) {
				self::raiseError($field_name, $error_message);
			}
			return $value;
		}
		
		/*
			Checks the given password values.
			
			@param $password1 The first password to check
			@param $password2 The second password to check
		*/
		public static function checkPassword($password1, $password2) {
			$value = '';
			$value1 = FormHandler::getFieldValue($password1);
			$value2 = FormHandler::getFieldValue($password2);
			
			if (($value1 == '' || is_null($value1)) && ($value2 != '' || !is_null($value2))) {
				self::raiseError($password1, 'Vul beide wachtwoordvelden in');
			} else if (($value2 == '' || is_null($value2)) && ($value1 != '' || !is_null($value1))) {
				self::raiseError($password2, 'Herhaal het wachtwoord');
			} else if ($value1 != $value2) {
				self::raiseError($password1, 'De wachtwoorden zijn niet gelijk');
			} else {
				$value = $value1;
			}
			
			return $value;
		}
		
		/*
			Raises an error for the given field.
			
			@param $field_name The fieldname to set the error for
			@param $error_message The error to set
		*/
		private static function raiseError($error_field, $error_message) {
			global $errors;
			$errors[$error_field . '_error'] = $error_message;
		}
	
	}
	
?>