<?php
    defined("_ACCESS") or die;

    require_once CMS_ROOT . 'frontend/handlers/error_type.php';

    class FormErrors {

        private static array $ERRORS = array();

        public static function raiseError(string $key, ErrorType $errorType): void {
            self::$ERRORS[$key] = $errorType;
        }

        public static function getError(string $key): ?ErrorType {
            if (isset(SELF::$ERRORS[$key])) {
                return SELF::$ERRORS[$key];
            }
            return null;
        }

        public static function getErrors(): array {
            return self::$ERRORS;
        }

        public static function hasErrors(): bool {
            return count(self::$ERRORS) > 0;
        }
    }
?>