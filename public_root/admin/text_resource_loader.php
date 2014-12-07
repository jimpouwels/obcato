<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "authentication/session.php";
    require_once CMS_ROOT . "utilities/string_utility.php";
    require_once CMS_ROOT . "database/dao/module_dao.php";

    class TextResourceLoader {

        private $_language;

        public function __construct($language) {
            $this->_language = $language;
        }

        public function loadTextResources() {
            $text_resources = $this->getGlobalTextResources();
            foreach (ModuleDao::getInstance()->getAllModules() as $module)
                $text_resources = array_merge($text_resources, $this->getModuleTextResources($module));
            return $text_resources;
        }

        private function getGlobalTextResources() {
            return $this->getTextResourcesFromFile(STATIC_DIR . '/text_resources/' . $this->_language . '.txt');
        }

        private function getModuleTextResources($module) {
            return $this->getTextResourcesFromFile(STATIC_DIR . '/text_resources/' . $module->getIdentifier() . '-' . $this->_language . '.txt');
        }

        private function getTextResourcesFromFile($file_path) {
            $resources = array();
            if (file_exists($file_path)) {
                $file = fopen($file_path, 'rb');
                while (!feof($file)) {
                    $line = fgets($file);
                    if (!$this->isComment($line) && !$this->isEmptyLine($line)) {
                        $parts = explode(':', $line);
                        if (count($parts) > 1)
                            $resources[trim($parts[0])] = trim($parts[1]);
                    }
                }
            }
            return $resources;
        }

        private function isComment($line) {
            return StringUtility::startsWith(trim($line), '#');
        }

        private function isEmptyLine($line) {
            return trim($line) == '';
        }

    }