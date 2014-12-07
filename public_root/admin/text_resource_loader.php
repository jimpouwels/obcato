<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "authentication/session.php";
    require_once CMS_ROOT . "utilities/string_utility.php";
    require_once CMS_ROOT . "database/dao/module_dao.php";

    class TextResourceLoader {

        private static $resource_cache;

        public static function loadTextResources() {
            if (self::$resource_cache) return;
            $language = Session::getCurrentLanguage();
            self::$resource_cache = self::getGlobalTextResources($language);
            foreach (ModuleDao::getInstance()->getAllModules() as $module)
                self::$resource_cache = array_merge(self::$resource_cache, self::getModuleTextResources($module, $language));
            Session::setValue('text_resources', self::$resource_cache);
        }

        public static function getTextResources() {
            return Session::getValue('text_resources');
        }

        public static function getTextResource($identifier) {
            $resources = Session::getValue('text_resources');
            if (isset($resources[$identifier]))
                return $resources[$identifier];
        }

        private static function getGlobalTextResources($language) {
            return self::getTextResourcesFromFile(STATIC_DIR . '/text_resources/' . $language . '.txt');
        }

        private static function getModuleTextResources($module, $language) {
            return self::getTextResourcesFromFile(STATIC_DIR . '/text_resources/' . $module->getIdentifier() . '-' . $language . '.txt');
        }

        private static function getTextResourcesFromFile($file_path) {
            $resources = array();
            if (file_exists($file_path)) {
                $file = fopen($file_path, 'rb');
                while (!feof($file)) {
                    $line = fgets($file);
                    if (!self::isComment($line) && !self::isEmptyLine($line)) {
                        $parts = explode(':', $line);
                        if (count($parts) > 1)
                            $resources[trim($parts[0])] = trim($parts[1]);
                    }
                }
            }
            return $resources;
        }

        private static function isComment($line) {
            return StringUtility::startsWith(trim($line), '#');
        }

        private static function isEmptyLine($line) {
            return trim($line) == '';
        }

    }