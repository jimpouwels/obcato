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
    }