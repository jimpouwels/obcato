<?php
require_once CMS_ROOT . "/authentication/Session.php";
require_once CMS_ROOT . "/utilities/StringUtility.php";

abstract class Form {

    public abstract function loadFields(): void;

    public function getMandatoryFieldValue(string $fieldName): string {
        $value = $this->getFieldValue($fieldName);
        if (!$value) {
            $this->raiseError($fieldName, "form_error_mandatory");
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

    public function getMandatoryEmailAddress(string $fieldName): ?string {
        $emailAddress = $this->getEmailAddress($fieldName);
        if (!$emailAddress) {
            $this->raiseError($fieldName, "form_error_mandatory");
        }
        return $emailAddress;
    }

    public function getEmailAddress(string $fieldName): ?string {
        $value = $this->getFieldValue($fieldName);
        $invalidEmail = preg_match("/^[A-Z0-9._%-]+@[A-Z0-9][A-Z0-9.-]{0,61}[A-Z0-9]\.[A-Z]{2,6}$/i", $value);
        if ($value && !$invalidEmail) {
            $this->raiseError($fieldName, "form_error_invalid_email");
        }
        return $value;
    }

    public function getMandatoryNumber(string $fieldName): ?int {
        $number = $this->getNumber($fieldName);
        if (!$number) {
            $this->raiseError($fieldName, "form_error_mandatory");
        }
        return $number;
    }

    public function getNumber(string $fieldName): ?int {
        $numberAsString = $this->getFieldValue($fieldName);
        if ($numberAsString && !is_numeric($numberAsString)) {
            $this->raiseError($fieldName, "form_error_invalid_number");
            return null;
        }
        return intval($numberAsString);
    }

    public function getMandatoryDate(string $fieldName): ?string {
        $date = $this->getDate($fieldName);
        if (!$date) {
            $this->raiseError($fieldName, "form_error_mandatory");
        }
        return $date;
    }

    public function getDate(string $fieldName): ?string {
        $value = $this->getFieldValue($fieldName);
        $validDate = preg_match("/^[0-3]?[0-9]\-[01]?[0-9]\-[12][90][0-9][0-9]$/", $value);
        if ($value && !$validDate) {
            $this->raiseError($fieldName, "form_error_invalid_date");
        }
        return $value;
    }

    public function getPassword(string $passwordField1, string $passwordField2): ?string {
        $value1 = $this->getFieldValue($passwordField1);
        $value2 = $this->getFieldValue($passwordField2);
        if ((!$value1 && $value2)) {
            $this->raiseError($passwordField1, "form_error_not_both_passwords_filled_in");
        } else if ($value1 && !$value2) {
            $this->raiseError($passwordField2, "form_error_not_both_passwords_filled_in");
        } else if ($value1 != $value2) {
            $this->raiseError($passwordField2, "form_error_passwords_mismatch");
        }
        return $value1;
    }

    public function getMandatoryUploadFilePath(string $fieldName): ?string {
        $file_path = $this->getUploadFilePath($fieldName);
        if ($file_path) {
            return $file_path;
        }
        $this->raiseError($fieldName, "form_error_mandatory");
        return null;
    }

    public function getUploadFilePath(string $fieldName): ?string {
        if (isset($_FILES[$fieldName]) && is_uploaded_file($_FILES[$fieldName]["tmp_name"])) {
            return $_FILES[$fieldName]["tmp_name"];
        }
        return null;
    }

    public function getUploadedFileName(string $fieldName): ?string {
        if (isset($_FILES[$fieldName]) && is_uploaded_file($_FILES[$fieldName]["tmp_name"])) {
            return $_FILES[$fieldName]["name"];
        }
        return null;
    }

    protected function hasErrors(): bool {
        return Session::getErrorCount() > 0;
    }

    protected function getError(string $fieldName): string {
        return Session::getError($fieldName);
    }

    protected function getTextResource(string $identifier): string {
        return Session::getTextResource($identifier);
    }

}
