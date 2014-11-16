<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "authentication/session.php";
    require_once CMS_ROOT . "utilities/string_utility.php";

    class TextResourceLoader {

        public function loadTextResources() {
            $resources = array();
            $language = Session::getCurrentLanguage();
            $file = fopen(STATIC_DIR . '/text_resources/' . $language . '.txt', 'rb');
            while (!feof($file)) {
                $line = fgets($file);
                if (!$this->isComment($line)) {
                    $parts = explode(':', $line);
                    $resources[trim($parts[0])] = trim($parts[1]);
                }
            }
            return $resources;
        }

        private function isComment($line) {
            return StringUtility::startsWith(trim($line), '#');
        }

    }