<?php

namespace Pageflow\Core\frontend\handlers;

class FormStatus {

    private const SESSION_KEY = 'pageflow_form';

    public static function raiseError(int $webformId, string $key, ErrorType $errorType): void {
        self::ensureSession();
        $_SESSION[self::SESSION_KEY][$webformId]['errors'][$key] = $errorType->value;
    }

    public static function getError(int $webformId, string $key): ?ErrorType {
        self::ensureSession();
        $value = $_SESSION[self::SESSION_KEY][$webformId]['errors'][$key] ?? null;
        return $value !== null ? ErrorType::from($value) : null;
    }

    public static function hasErrors(int $webformId): bool {
        self::ensureSession();
        return !empty($_SESSION[self::SESSION_KEY][$webformId]['errors']);
    }

    public static function clearErrors(int $webformId): void {
        self::ensureSession();
        unset($_SESSION[self::SESSION_KEY][$webformId]['errors']);
    }

    public static function setSubmittedForm(int $webformId): void {
        self::ensureSession();
        $_SESSION[self::SESSION_KEY][$webformId]['submitted'] = true;
    }

    public static function isSubmitted(int $webformId): bool {
        self::ensureSession();
        if (!empty($_SESSION[self::SESSION_KEY][$webformId]['submitted'])) {
            unset($_SESSION[self::SESSION_KEY][$webformId]['submitted']);
            return true;
        }
        return false;
    }

    public static function getFieldValue(string $name): ?string {
        return $_POST[$name] ?? null;
    }

    private static function ensureSession(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}