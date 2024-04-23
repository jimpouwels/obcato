<?php

namespace Obcato\Core\authentication;

class Session {

    public static function getCurrentLanguage(): string {
        return $_SESSION['language'];
    }

    public static function setCurrentLanguage(string $language): void {
        $_SESSION['language'] = $language;
    }

    public static function setTextResources(array $textResources): void {
        $_SESSION['text_resources'] = $textResources;
    }

    public static function getTextResources(): array {
        return $_SESSION['text_resources'];
    }

    public static function areTextResourcesLoaded(): bool {
        return isset($_SESSION['text_resources']);
    }

    public static function addFieldError(string $fieldName, string $errorMessageResourceIdentifier): void {
        if (!isset($_SESSION['errors'])) {
            $_SESSION['errors'] = array();
        }
        if (!self::hasError($fieldName)) {
            $errorMessage = self::getTextResource($errorMessageResourceIdentifier);
            if (!$errorMessage) {
                $errorMessage = $errorMessageResourceIdentifier;
            }
            $_SESSION['errors'][$fieldName . '_error'] = $errorMessage;
        }
    }

    public static function hasError(string $fieldName): bool {
        return isset($_SESSION['errors'][$fieldName . '_error']);
    }

    public static function getTextResource(string $name): string {
        return $_SESSION['text_resources'][$name] ?? $name;
    }

    public static function getErrorCount(): int {
        return count($_SESSION['errors']);
    }

    public static function popError(string $fieldName): string {
        $error = self::getError($fieldName);
        unset($_SESSION['errors'][$fieldName . '_error']);
        return $error;
    }

    public static function getError(string $fieldName): ?string {
        return $_SESSION['errors'][$fieldName . '_error'] ?? null;
    }

    public static function clearErrors(): void {
        $_SESSION['errors'] = array();
    }
}