<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/authentication/Session.php";
require_once CMS_ROOT . "/utilities/StringUtility.php";

abstract class Form {

    public abstract function loadFields(): void;

    public function getMandatoryFieldValue(string $fieldName, string $errorMessageResourceIdentifier): string {
        $value = $this->getFieldValue($fieldName);
        if ($this->isEmpty($value)) {
            $this->raiseError($fieldName, $errorMessageResourceIdentifier);
        }
        return $value;
    }

    public function getFieldValue(string $fieldName): ?string {
        $value = null;
        if (isset($_POST[$fieldName])) {
            $value = $_POST[$fieldName];
        }
        return $value;
    }

    protected function isEmpty(?string $value): bool {
        return empty($value) || $value == "";
    }

    protected function raiseError(string $errorField, string $errorMessageResourceIdentifier): void {
        Session::addFieldError($errorField, $errorMessageResourceIdentifier);
    }

    public function getFieldValues(string $fieldName): array {
        $value = array();
        if (isset($_POST[$fieldName])) {
            $value = $_POST[$fieldName];
        }
        return $value;
    }

    public function getSelectValue(string $fieldName): array {
        $value = [];
        if (isset($_POST[$fieldName])) {
            $value = $_POST[$fieldName];
        }
        return $value;
    }

    public function getCheckboxValue(string $fieldName): int {
        return $this->getFieldValue($fieldName) == "on" ? 1 : 0;
    }

    public function getMandatoryEmailAddress(string $fieldName, string $errorMessageResourceIdentifier, string $invalidEmailMessage): ?string {
        $emailAddress = $this->getEmailAddress($fieldName, $invalidEmailMessage);
        if ($this->isEmpty($emailAddress)) {
            $this->raiseError($fieldName, $errorMessageResourceIdentifier);
        }
        return $emailAddress;
    }

    public function getEmailAddress(string $fieldName, string $errorMessageResourceIdentifier): ?string {
        $value = $this->getFieldValue($fieldName);
        $invalidEmail = preg_match("/^[A-Z0-9._%-]+@[A-Z0-9][A-Z0-9.-]{0,61}[A-Z0-9]\.[A-Z]{2,6}$/i", $value);
        if (!$this->isEmpty($value) && !$invalidEmail) {
            $this->raiseError($fieldName, $errorMessageResourceIdentifier);
        }
        return $value;
    }

    public function getMandatoryNumber(string $fieldName, string $errorMessageResourceIdentifier, string $invalidNumberMessage): ?int {
        $number = $this->getNumber($fieldName, $invalidNumberMessage);
        if ($this->isEmpty($number)) {
            $this->raiseError($fieldName, $errorMessageResourceIdentifier);
        }
        return $number;
    }

    public function getNumber(string $field_name, string $error_message_resource_identifier): ?int {
        $number_as_string = $this->getFieldValue($field_name);
        if (!$this->isEmpty($number_as_string) && !is_numeric($number_as_string)) {
            $this->raiseError($field_name, $error_message_resource_identifier);
        }
        return !$this->isEmpty($number_as_string) ? intval($number_as_string) : null;
    }

    public function getMandatoryDate(string $field_name, string $error_message_resource_identifier, string $invalid_date_message): ?string {
        $date = $this->getDate($field_name, $invalid_date_message);
        if ($this->isEmpty($date)) {
            $this->raiseError($field_name, $error_message_resource_identifier);
        }
        return $date;
    }

    public function getDate(string $field_name, string $error_message_resource_identifier): ?string {
        $value = $this->getFieldValue($field_name);
        $valid_date = preg_match("/^[0-3]?[0-9]\-[01]?[0-9]\-[12][90][0-9][0-9]$/", $value);
        if (!$valid_date && $value != '') {
            $this->raiseError($field_name, $error_message_resource_identifier);
        }
        return $value;
    }

    public function getPassword(string $password1, string $password2) {
        $value1 = $this->getFieldValue($password1);
        $value2 = $this->getFieldValue($password2);
        if ((!$value1 && $value2)) {
            $this->raiseError($password1, "Vul beide wachtwoordvelden in");
        } else if ($value1 && !$value2) {
            $this->raiseError($password2, "Herhaal het wachtwoord");
        } else if ($value1 != $value2) {
            $this->raiseError($password2, "De wachtwoorden zijn niet gelijk");
        }
        return $value1;
    }

    public function getMandatoryUploadFilePath(string $field_name, string $error_message_resource_identifier): ?string {
        $file_path = $this->getUploadFilePath($field_name);
        if ($file_path) {
            return $file_path;
        }
        $this->raiseError($field_name, $error_message_resource_identifier);
        return null;
    }

    public function getUploadFilePath(string $field_name): ?string {
        if (isset($_FILES[$field_name]) && is_uploaded_file($_FILES[$field_name]["tmp_name"])) {
            return $_FILES[$field_name]["tmp_name"];
        }
        return null;
    }

    public function getUploadedFileName(string $field_name): ?string {
        if (isset($_FILES[$field_name]) && is_uploaded_file($_FILES[$field_name]["tmp_name"])) {
            return $_FILES[$field_name]["name"];
        }
        return null;
    }

    protected function hasErrors(): bool {
        return Session::getErrorCount() > 0;
    }

    protected function getError(string $field_name): string {
        return Session::getError($field_name);
    }

    protected function getTextResource(string $identifier): string {
        return Session::getTextResource($identifier);
    }

}
