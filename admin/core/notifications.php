<?php
    defined('_ACCESS') or die;
    
    class Notifications {
        
        public static function setSuccessMessage(string $message): void {
            if (!self::getMessage()) {
                $_SESSION['success'] = true;
                self::setMessage($message);
            }
        }
        
        public static function setFailedMessage(string $message): void {
            if (!self::getMessage()) {
                $_SESSION['success'] = false;
                self::setMessage($message);
            }
        }
        
        public static function getMessage(): ?string {
            if (isset($_SESSION['cms_notification'])) {
                return $_SESSION['cms_notification'];
            }
            return null;
        }
        
        public static function getSuccess(): ?string {
            $success = null;
            if (isset($_SESSION['success'])) {
                $success = $_SESSION['success'];
            }
            return $success;
        }
        
        public static function clearMessage(): void {
            if (isset($_SESSION['cms_notification'])) {
                unset($_SESSION['cms_notification']);
            }
            if (isset($_SESSION['success'])) {
                unset($_SESSION['success']);
            }
        }

        private static function setMessage(string $message): void {
            $_SESSION['cms_notification'] = $message;
        }
    
    }
?>