<?php
    defined('_ACCESS') or die;

    class Session {

        public static function getCurrentLanguage(): string {
            return $_SESSION['language'];
        }

        public static function setCurrentLanguage($language): void {
            $_SESSION['language'] = $language;
        }

        public static function setTextResources($text_resources): void {
            $_SESSION['text_resources'] = $text_resources;
        }

        public static function getTextResources(): array {
            return $_SESSION['text_resources'];
        }

        public static function getTextResource($name): string {
            if (isset($_SESSION['text_resources'][$name])) {
                return $_SESSION['text_resources'][$name];
            }
            return "";
        }

        public static function areTextResourcesLoaded(): bool {
            return isset($_SESSION['text_resources']);
        }

        public static function addFieldError($field_name, $error_message): void {
            if (!isset($_SESSION['errors'])) {
                $_SESSION['errors'] = array();
            }
            if (!self::hasError($field_name)) {
                $_SESSION['errors'][$field_name . '_error'] = $error_message;
            }
        }

        public static function popError($field_name): string {
            $error = self::getError($field_name);
            unset($_SESSION['errors'][$field_name . '_error']);
            return $error;
        }

        public static function getError($field_name): string {
            if (isset($_SESSION['errors'][$field_name . '_error'])) {
                return $_SESSION['errors'][$field_name . '_error'];
            }
            return null;
        }

        public static function hasError($field_name): bool {
            return isset($_SESSION['errors'][$field_name . '_error']);
        }
    }