<?php

namespace Obcato\Core\admin\authentication;

class Session implements \Obcato\ComponentApi\Session {

    private static Session $instance;

    private function __construct() {}

    public static function getInstance(): Session {
        if (!self::$instance) {
            self::$instance = new Session();
        }
        return self::$instance;
    }

    public function getCurrentLanguage(): string {
        return $_SESSION['language'];
    }

    public function setCurrentLanguage(string $language): void {
        $_SESSION['language'] = $language;
    }

    public function setTextResources(array $textResources): void {
        $_SESSION['text_resources'] = $textResources;
    }

    public function getTextResources(): array {
        return $_SESSION['text_resources'];
    }

    public function areTextResourcesLoaded(): bool {
        return isset($_SESSION['text_resources']);
    }

    public function addFieldError(string $fieldName, string $errorMessageResourceIdentifier): void {
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

    public function hasError(string $fieldName): bool {
        return isset($_SESSION['errors'][$fieldName . '_error']);
    }

    public function getTextResource(string $name): string {
        if (isset($_SESSION['text_resources'][$name])) {
            return $_SESSION['text_resources'][$name];
        }
        return $name;
    }

    public function getErrorCount(): int {
        return count($_SESSION['errors']);
    }

    public function popError(string $fieldName): string {
        $error = self::getError($fieldName);
        unset($_SESSION['errors'][$fieldName . '_error']);
        return $error;
    }

    public function getError(string $fieldName): ?string {
        if (isset($_SESSION['errors'][$fieldName . '_error'])) {
            return $_SESSION['errors'][$fieldName . '_error'];
        }
        return null;
    }

    public function clearErrors(): void {
        $_SESSION['errors'] = array();
    }
}