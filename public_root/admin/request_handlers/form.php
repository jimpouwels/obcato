<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "authentication/session.php";
    require_once CMS_ROOT . "utilities/string_utility.php";
    
    abstract class Form {

        private $_error_count;

        public abstract function loadFields();
        
        public function getMandatoryFieldValue($field_name, $error_message) {
            $value = $this->getFieldValue($field_name);
            if ($this->isEmpty($value)) {
                $this->raiseError($field_name, $error_message);    
            }
            return $value;
        }
        
        public function getFieldValue($field_name) {
            $value = null;
            if (isset($_POST[$field_name]))
                $value = $_POST[$field_name];
            return $value;
        }
        
        public function getCheckboxValue($field_name) {
            return $this->getFieldValue($field_name) == "on" ? 1 : 0;
        }
        
        public function getMandatoryEmailAddress($field_name, $error_message, $invalid_email_message) {
            $email_address = $this->getEmailAddress($field_name, $invalid_email_message);
            if ($this->isEmpty($email_address))
                $this->raiseError($field_name, $error_message);
            return $email_address;
        }
        
        public function getEmailAddress($field_name, $error_message) {
            $value = $this->getFieldValue($field_name);
            $valid_email = preg_match("/^[A-Z0-9._%-]+@[A-Z0-9][A-Z0-9.-]{0,61}[A-Z0-9]\.[A-Z]{2,6}$/i", $value);
            if (!$this->isEmpty($value) && !$valid_email)
                $this->raiseError($field_name, $error_message);
            return $value;
        }
        
        public function getMandatoryNumber($field_name, $error_message, $invalid_number_message) {
            $number = $this->getNumber($field_name, $invalid_number_message);
            if ($this->isEmpty($number))
                $this->raiseError($field_name, $error_message);
            return $number;
        }
        
        public function getNumber($field_name, $error_message) {
            $number = $this->getFieldValue($field_name);
            if (!$this->isEmpty($number) && !is_numeric($number))
                $this->raiseError($field_name, $error_message);
            return $number;
        }
        
        public function getMandatoryDate($field_name, $error_message, $invalid_date_message) {
            $date = $this->getDate($field_name, $invalid_date_message);
            if ($this->isEmpty($date))
                $this->raiseError($field_name, $error_message);
            return $date;
        }
        
        public function getDate($field_name, $error_message) {
            $value = $this->getFieldValue($field_name);
            $valid_date = preg_match("/^[0-3]?[0-9]\-[01]?[0-9]\-[12][90][0-9][0-9]$/", $value);
            if (!$valid_date && $value != '')
                $this->raiseError($field_name, $error_message);
            return $value;
        }
        
        public function getPassword($password1, $password2) {
            $value1 = $this->getFieldValue($password1);
            $value2 = $this->getFieldValue($password2);
            if ((!$value1 && $value2))
                $this->raiseError($password1, "Vul beide wachtwoordvelden in");
            else if ($value1 && !$value2)
                $this->raiseError($password2, "Herhaal het wachtwoord");
            else if ($value1 != $value2)
                $this->raiseError($password2, "De wachtwoorden zijn niet gelijk");
            return $value1;
        }

        public function getMandatoryUploadFilePath($field_name, $error_message) {
            $file_path = $this->getUploadFilePath($field_name);
            if ($file_path)
                return $file_path;
            $this->raiseError($field_name, $error_message);
        }
        
        public function getUploadFilePath($field_name) {
            if (isset($_FILES[$field_name]) && is_uploaded_file($_FILES[$field_name]["tmp_name"]))
                return $_FILES[$field_name]["tmp_name"];
        }
        
        public function getUploadedFileName($field_name) {
            if (isset($_FILES[$field_name]) && is_uploaded_file($_FILES[$field_name]["tmp_name"]))
                return $_FILES[$field_name]["name"];
        }
        
        protected function hasErrors() {
            return $this->_error_count > 0;
        }
        
        protected function raiseError($error_field, $error_message) {
            Session::addFieldError($error_field, $error_message);
            $this->_error_count++;
        }
        
        protected function isEmpty($value) {
            return empty($value) || $value == "";
        }

        protected function getError($field_name) {
            return Session::getError($field_name);
        }

        private function hasError($field_name) {
            return Session::hasError($field_name);
        }

        protected function getTextResource($identifier) {
            return Session::getTextResource($identifier);
        }
    
    }
    
    class FormException extends Exception {

        public function __construct($error_message = '') {
            parent::__construct($error_message);
        }

    }
    
?>
