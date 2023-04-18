<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "authentication/session.php";
    require_once CMS_ROOT . "utilities/string_utility.php";
    
    abstract class Form {

        private int $_error_count = 0;

        public abstract function loadFields(): void;
        
        public function getMandatoryFieldValue(string $field_name, string $error_message) {
            $value = $this->getFieldValue($field_name);
            if ($this->isEmpty($value)) {
                $this->raiseError($field_name, $error_message);    
            }
            return $value;
        }
        
        public function getFieldValue(string $field_name): ?string {
            $value = null;
            if (isset($_POST[$field_name])) {
                $value = $_POST[$field_name];
            }
            return $value;
        }
        
        public function getFieldValues(string $field_name): array {
            $value = array();
            if (isset($_POST[$field_name])) {
                $value = $_POST[$field_name];
            }
            return $value;
        }

        public function getSelectValue(string $field_name): array {
            $value = [];
            if (isset($_POST[$field_name])) {
                $value = $_POST[$field_name];
            }
            return $value;
        }

        public function getCheckboxValue(string $field_name): int {
            return $this->getFieldValue($field_name) == "on" ? 1 : 0;
        }
        
        public function getMandatoryEmailAddress(string $field_name, string $error_message, string $invalid_email_message): string {
            $email_address = $this->getEmailAddress($field_name, $invalid_email_message);
            if ($this->isEmpty($email_address)) {
                $this->raiseError($field_name, $error_message);
            }
            return $email_address;
        }
        
        public function getEmailAddress(string $field_name, string $error_message): ?string {
            $value = $this->getFieldValue($field_name);
            $valid_email = preg_match("/^[A-Z0-9._%-]+@[A-Z0-9][A-Z0-9.-]{0,61}[A-Z0-9]\.[A-Z]{2,6}$/i", $value);
            if (!$this->isEmpty($value) && !$valid_email) {
                $this->raiseError($field_name, $error_message);
            }
            return $value;
        }
        
        public function getMandatoryNumber(string $field_name, string $error_message, string $invalid_number_message): int {
            $number = $this->getNumber($field_name, $invalid_number_message);
            if ($this->isEmpty($number)) {
                $this->raiseError($field_name, $error_message);
            }
            return $number;
        }
        
        public function getNumber(string $field_name, string $error_message): ?int {
            $number = $this->getFieldValue($field_name);
            if (!$this->isEmpty($number) && !is_numeric($number)) {
                $this->raiseError($field_name, $error_message);
            }
            return intval($number);
        }
        
        public function getMandatoryDate(string $field_name, string $error_message, string $invalid_date_message) {
            $date = $this->getDate($field_name, $invalid_date_message);
            if ($this->isEmpty($date))
                $this->raiseError($field_name, $error_message);
            return $date;
        }
        
        public function getDate(string $field_name, string $error_message) {
            $value = $this->getFieldValue($field_name);
            $valid_date = preg_match("/^[0-3]?[0-9]\-[01]?[0-9]\-[12][90][0-9][0-9]$/", $value);
            if (!$valid_date && $value != '')
                $this->raiseError($field_name, $error_message);
            return $value;
        }
        
        public function getPassword(string $password1, string $password2) {
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

        public function getMandatoryUploadFilePath(string $field_name, string $error_message) {
            $file_path = $this->getUploadFilePath($field_name);
            if ($file_path)
                return $file_path;
            $this->raiseError($field_name, $error_message);
        }
        
        public function getUploadFilePath(string $field_name) {
            if (isset($_FILES[$field_name]) && is_uploaded_file($_FILES[$field_name]["tmp_name"]))
                return $_FILES[$field_name]["tmp_name"];
        }
        
        public function getUploadedFileName(string $field_name) {
            if (isset($_FILES[$field_name]) && is_uploaded_file($_FILES[$field_name]["tmp_name"]))
                return $_FILES[$field_name]["name"];
        }
        
        protected function hasErrors() {
            return $this->_error_count > 0;
        }
        
        protected function raiseError(string $error_field, string $error_message) {
            Session::addFieldError($error_field, $error_message);
            $this->_error_count++;
        }
        
        protected function isEmpty(string $value): bool {
            return empty($value) || $value == "";
        }

        protected function getError(string $field_name): string {
            return Session::getError($field_name);
        }

        private function hasError(string $field_name): bool {
            return Session::hasError($field_name);
        }

        protected function getTextResource(string $identifier): string {
            return Session::getTextResource($identifier);
        }
    
    }
    
    class FormException extends Exception {

        public function __construct(string $error_message = '') {
            parent::__construct($error_message);
        }

    }
    
?>
