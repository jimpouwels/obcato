<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "authentication/session.php";
    require_once CMS_ROOT . "utilities/string_utility.php";
    require_once CMS_ROOT . "database/dao/module_dao.php";

    class TextResourceLoader {

        private static $_resources = array();

        public static function loadTextResources() {
            $language = Session::getCurrentLanguage();
            self::$_resources = array_merge(self::$_resources, self::getGlobalTextResources($language));
            foreach (ModuleDao::getInstance()->getAllModules() as $module)
                self::$_resources = array_merge(self::$_resources, self::getModuleTextResources($module, $language));
        }

        public static function getTextResources() {
            return self::$_resources;
        }

        public static function getTextResource($identifier) {
            if (isset(self::$_resources[$identifier]))
                return self::$_resources[$identifier];
        }

        private static function getGlobalTextResources($language) {
            return self::getTextResourcesFromFile(STATIC_DIR . '/text_resources/' . $language . '.txt');
        }

        private static function getModuleTextResources($module, $language) {
            return self::getTextResourcesFromFile(STATIC_DIR . '/text_resources/' . $module->getIdentifier() . '-' . $language . '.txt');
        }

        private static function getTextResourcesFromFile($file_path) {
            $resources = array();
            $file = fopen($file_path, 'rb');
            while (!feof($file)) {
                $line = fgets($file);
                if (!self::isComment($line)) {
                    $parts = explode(':', $line);
                    $resources[trim($parts[0])] = trim($parts[1]);
                }
            }
            return $resources;
        }

        private static function isComment($line) {
            return StringUtility::startsWith(trim($line), '#');
        }

    }