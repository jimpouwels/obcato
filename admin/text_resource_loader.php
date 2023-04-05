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

        public function loadTextResources(): array {
            $text_resources = $this->getGlobalTextResources();
            foreach (ModuleDao::getInstance()->getAllModules() as $module) {
                $text_resources = array_merge($text_resources, $this->getModuleTextResources($module));
            }
            return $text_resources;
        }

        private function getGlobalTextResources(): array {
            return $this->getTextResourcesFromFile(STATIC_DIR . '/text_resources/' . $this->_language . '.txt');
        }

        private function getModuleTextResources($module): array {
            return $this->getTextResourcesFromFile($this->getModuleResourceFilePath($module->getIdentifier()));
        }

        private function getTextResourcesFromFile($file_path): array {
            $resources = array();
            if (file_exists($file_path)) {
                $file = fopen($file_path, 'rb');
                while (!feof($file)) {
                    $line = fgets($file);
                    if (!$this->isComment($line) && !$this->isEmptyLine($line)) {
                        $parts = explode(':', $line);
                        if (count($parts) > 1) {
                            $resources[trim($parts[0])] = trim($parts[1]);
                        }
                    }
                }
            }
            return $resources;
        }

        private function getModuleResourceFilePath($module_identifier): string {
            $path = STATIC_DIR . '/text_resources/' . $module_identifier . '-' . $this->_language . '.txt';
            if (!file_exists($path)) {
                $path = STATIC_DIR . '/text_resources/' . $module_identifier . '-nl.txt';
            }
            return $path;
        }

        private function isComment($line): bool {
            return StringUtility::startsWith(trim($line), '#');
        }

        private function isEmptyLine($line): bool {
            return trim($line) == '';
        }

    }