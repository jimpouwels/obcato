<?php
    defined('_ACCESS') or die;

    class Session {

        public static function getCurrentLanguage() {
            return $_SESSION['language'];
        }

        public static function setCurrentLanguage($language) {
            $_SESSION['language'] = $language;
        }

        public static function setTextResources($text_resources) {
            $_SESSION['text_resources'] = $text_resources;
        }

        public static function getTextResources() {
            return $_SESSION['text_resources'];
        }

        public static function getTextResource($name) {
            if (isset($_SESSION['text_resources'][$name]))
                return $_SESSION['text_resources'][$name];
        }

        public static function areTextResourcesLoaded() {
            return isset($_SESSION['text_resources']);
        }

        public static function addFieldError($field_name, $error_message) {
            if (!isset($_SESSION['errors']))
                $_SESSION['errors'] = array();
            if (!self::hasError($field_name))
                $_SESSION['errors'][$field_name . '_error'] = $error_message;
        }

        public static function popError($field_name) {
            $error = self::getError($field_name);
            unset($_SESSION['errors'][$field_name . '_error']);
            return $error;
        }

        public static function getError($field_name) {
            if (isset($_SESSION['errors'][$field_name . '_error']))
                return $_SESSION['errors'][$field_name . '_error'];
        }

        public static function hasError($field_name) {
            return isset($_SESSION['errors'][$field_name . '_error']);
        }
    }