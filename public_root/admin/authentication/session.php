<?php
    defined('_ACCESS') or die;

    class Session {

        public static function getCurrentLanguage() {
            return $_SESSION['language'];
        }

        public static function setCurrentLanguage($language) {
            $_SESSION['language'] = $language;
        }

    }