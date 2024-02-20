<?php

namespace Obcato\Core;

class FormStatus {

    private static array $ERRORS = array();
    private static ?int $SUBMITTED_FORM = null;

    public static function raiseError(string $key, ErrorType $errorType): void {
        self::$ERRORS[$key] = $errorType;
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

    public static function setSubmittedForm(int $webformId): void {
        self::$SUBMITTED_FORM = $webformId;
    }

    public static function getFieldValue(string $name): ?string {
        if (isset($_POST[$name])) {
            return $_POST[$name];
        }
        return null;
    }
}

?>