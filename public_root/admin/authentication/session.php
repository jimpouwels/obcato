<?php
    defined('_ACCESS') or die;

    class Session {

        public static function getCurrentLanguage() {
            return $_SESSION['language'];
        }

        public static function setCurrentLanguage($language) {
            $_SESSION['language'] = $language;
        }

        public static function setValue($name, $text_resources) {
            $_SESSION[$name] = $text_resources;
        }

        public static function getValue($name) {
            if (isset($_SESSION[$name]))
                return $_SESSION[$name];
        }
    }