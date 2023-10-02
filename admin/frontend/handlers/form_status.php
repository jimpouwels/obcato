<?php
defined("_ACCESS") or die;

require_once CMS_ROOT . '/frontend/handlers/error_type.php';

class FormStatus {

    private static array $ERRORS = array();
    private static ?int $SUBMITTED_FORM = null;

    public static function raiseError(string $key, ErrorType $error_type): void {
        self::$ERRORS[$key] = $error_type;
    }

    public static function getError(string $key): ?ErrorType {
        if (isset(self::$ERRORS[$key])) {
            return self::$ERRORS[$key];
        }
        return null;
    }

    public static function getErrors(): array {
        return self::$ERRORS;
    }

    public static function hasErrors(): bool {
        return count(self::$ERRORS) > 0;
    }

    public static function getSubmittedForm(): ?int {
        return self::$SUBMITTED_FORM;
    }

    public static function setSubmittedForm(int $webform_id): void {
        self::$SUBMITTED_FORM = $webform_id;
    }

    public static function getFieldValue(string $name): ?string {
        if (isset($_POST[$name])) {
            return $_POST[$name];
        }
        return null;
    }
}

?>