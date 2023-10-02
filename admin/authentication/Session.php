<?php
defined('_ACCESS') or die;

class Session {

    public static function getCurrentLanguage(): string {
        return $_SESSION['language'];
    }

    public static function setCurrentLanguage(string $language): void {
        $_SESSION['language'] = $language;
    }

    public static function setTextResources(array $text_resources): void {
        $_SESSION['text_resources'] = $text_resources;
    }

    public static function getTextResources(): array {
        return $_SESSION['text_resources'];
    }

    public static function areTextResourcesLoaded(): bool {
        return isset($_SESSION['text_resources']);
    }

    public static function addFieldError(string $field_name, string $error_message_resource_identifier): void {
        if (!isset($_SESSION['errors'])) {
            $_SESSION['errors'] = array();
        }
        if (!self::hasError($field_name)) {
            $error_message = self::getTextResource($error_message_resource_identifier);
            if (!$error_message) {
                $error_message = $error_message_resource_identifier;
            }
            $_SESSION['errors'][$field_name . '_error'] = $error_message;
        }
    }

    public static function hasError(string $field_name): bool {
        return isset($_SESSION['errors'][$field_name . '_error']);
    }

    public static function getTextResource(string $name): string {
        if (isset($_SESSION['text_resources'][$name])) {
            return $_SESSION['text_resources'][$name];
        }
        return $name;
    }

    public static function getErrorCount(): int {
        return count($_SESSION['errors']);
    }

    public static function popError(string $field_name): string {
        $error = self::getError($field_name);
        unset($_SESSION['errors'][$field_name . '_error']);
        return $error;
    }

    public static function getError(string $field_name): ?string {
        if (isset($_SESSION['errors'][$field_name . '_error'])) {
            return $_SESSION['errors'][$field_name . '_error'];
        }
        return null;
    }

    public static function clearErrors(): void {
        $_SESSION['errors'] = array();
    }
}