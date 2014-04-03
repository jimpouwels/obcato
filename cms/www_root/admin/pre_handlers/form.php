<?php
	// No direct access
	defined('_ACCESS') or die;
	
	abstract class Form {
		
		public abstract function LoadFields();
		
		public function getMandatoryFieldValue($field_name) {
			if ($this->isEmpty($field_name)) throw new MandatoryFieldEmptyException($field_name . " must not be emtpy");
			return $this->getFieldValue($field_name);
		}
		
		public function getFieldValue($field_name) {
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
		
		public function getCheckboxValue($field_name) {
			return $this->getFieldValue($field_name) == "on" ? 1 : 0;
		}
		
		public function getMandatoryEmailAddress($field_name) {
			if ($this->isEmpty($field_name)) throw new MandatoryFieldEmptyException($field_name . " must not be emtpy");
			return $this->getEmailAddress($field_name);
		}
		
		public function getEmailAddress($field_name) {
			$value = $this->getFieldValue($field_name);
			$valid_email = preg_match("/^[A-Z0-9._%-]+@[A-Z0-9][A-Z0-9.-]{0,61}[A-Z0-9]\.[A-Z]{2,6}$/i", $value);
			if (!$valid_email) {
				throw new InvalidEmailAddressException("Email address in " . $field_name . " is invalid");
			}
			return $value;
		}
		
		public function getMandatoryNumber($field_name) {
			if ($this->isEmpty($field_name)) throw new MandatoryFieldEmptyException($field_name . " must not be emtpy");
			return $this->getNumber($field_name);
		}
		
		public function getNumber($field_name) {
			$value = $this->getFieldValue($field_name);
			if (!is_numeric($value)) {
				throw new NotANumberException("The field " . $field_name . " is expected to be a number, but was " . $value);
			}
			return $value;
		}
		
		public function getMandatoryDate($field_name, $error) {
			if ($this->isEmpty($field_name)) throw new MandatoryFieldEmptyException($field_name . " must not be emtpy");
			return $this->getDate($field_name);
		}
		
		public function getDate($field_name) {
			$value = $this->getFieldValue($field_name);
			$valid_date = preg_match("/^[0-3]?[0-9]\-[01]?[0-9]\-[12][90][0-9][0-9]$/", $value);
			if (!$valid_date) {
				throw new InvalidDateException($field_name . " is not a valid date");
			}
			return $value;
		}
		
		public function getPassword($password1, $password2) {
			$value = "";
			$value1 = $this->getFieldValue($password1);
			$value2 = $this->getFieldValue($password2);
			
			if (($value1 == "" || is_null($value1)) && ($value2 != "" || !is_null($value2))) {
				throw new InvalidPasswordException("Vul beide wachtwoordvelden in");
			} else if (($value2 == "" || is_null($value2)) && ($value1 != "" || !is_null($value1))) {
				throw new InvalidPasswordException("Herhaal het wachtwoord");
			} else if ($value1 != $value2) {
				throw new InvalidPasswordException("De wachtwoorden zijn niet gelijk");
			} else {
				$value = $value1;
			}
			return $value;
		}
		
		private function isEmpty($field_name) {
			$value = FormHandler::getFieldValue($field_name);
			if (is_null($value) || $value == "") {
				return true;
			}
			return false;
		}
		
		private function raiseError($error_field, $error_message) {
			global $errors;
			$errors[$error_field . "_error"] = $error_message;
		}
	
	}
	
	class FormException extends Exception {
	}
	
	class MandatoryFieldEmptyException extends FormException {
	}
	
	class NotANumberException extends FormException {
	}
?>