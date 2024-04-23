<?php

namespace Obcato\Core\core\model;

class Notifications {

    public static function setSuccessMessage(string $message): void {
        if (!self::getMessage()) {
            $_SESSION['success'] = true;
            self::setMessage($message);
        }
    }

    public static function getMessage(): ?string {
        return $_SESSION['cms_notification'] ?? null;
    }

    private static function setMessage(string $message): void {
        $_SESSION['cms_notification'] = $message;
    }

    public static function setFailedMessage(string $message): void {
        if (!self::getMessage()) {
            $_SESSION['success'] = false;
            self::setMessage($message);
        }
    }

    public static function getSuccess(): ?string {
        return $_SESSION['success'] ?? null;
    }

    public static function clearMessage(): void {
        if (isset($_SESSION['cms_notification'])) {
            unset($_SESSION['cms_notification']);
        }
        if (isset($_SESSION['success'])) {
            unset($_SESSION['success']);
        }
    }

}