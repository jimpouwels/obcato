<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "authentication/session.php";

    class TextResourceLoader {

        public function loadTextResources() {
            $resources = array();
            $language = Session::getCurrentLanguage();
            $file = fopen(STATIC_DIR . '/text_resources/' . $language . '.txt', 'rb');
            while (!feof($file)) {
                $parts = explode(':', fgets($file));
                $resources[trim($parts[0])] = trim($parts[1]);
            }
            return $resources;
        }

    }