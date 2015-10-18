<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'database/dao/friendly_url_dao.php';
    require_once CMS_ROOT . 'database/dao/settings_dao.php';
    require_once CMS_ROOT . 'database/dao/page_dao.php';

    class FriendlyUrlManager {

        private $_friendly_url_dao;
        private $_settings_dao;
        private $_page_dao;

        public function __construct() {
            $this->_friendly_url_dao = FriendlyUrlDao::getInstance();
            $this->_settings_dao = SettingsDao::getInstance();
            $this->_page_dao = PageDao::getInstance();
            $this->writeHtaccessFileIfNotExists();
        }

        public function insertOrUpdateFriendlyUrlForPage($page) {
            $url = '/' . $this->createNewUrlFor($page);
            $url = $this->appendNumberIfFriendlyUrlExists($url, $page);
            $this->_friendly_url_dao->insertOrUpdateFriendlyUrl($url, $page);
        }

        public function getPageFromUrl($url) {
            $url = rtrim($url, '/');
            return $this->_friendly_url_dao->getPageFromUrl($url);
        }

        public function getFriendlyUrlForPage($page) {
            return $this->_friendly_url_dao->getUrlFromPage($page);
        }

        private function createNewUrlFor($page) {
            $url = $this->replaceSpecialCharacters($page->getNavigationTitle());
            $parent_page = $page->getParent();
            if ($parent_page != null && $parent_page->getId() != $this->_page_dao->getRootPage()->getId())
                $url = $this->createNewUrlFor($page->getParent()) . "/" . $url;
            return $url;
        }

        private function appendNumberIfFriendlyUrlExists($url, $page) {
            $new_url = $url;
            $existing_page = $this->getPageFromUrl($url);
            $number = 1;
            while ($existing_page != null && $existing_page->getId() != $page->getId()) {
                $new_url = $url . $number;
                $number++;
                $existing_page = $this->getPageFromUrl($new_url);
            }
            return $new_url;
        }

        private function replaceSpecialCharacters($value) {
            $value = strtolower($value);
            $value = str_replace(' ', '-', $value);
            $value = preg_replace('/[^a-z-0-9]/', '', $value);
            return $value;
        }

        private function writeHtaccessFileIfNotExists() {
            $public_root_dir = $this->_settings_dao->getSettings()->getPublicRootDir();
            $htaccess_file_path = $public_root_dir . '/.htaccess';
            if (file_exists($htaccess_file_path)) return;
            $handle = fopen($htaccess_file_path, 'w');
            fclose($handle);
            file_put_contents($htaccess_file_path, "RewriteEngine on\n" .
                                                   "RewriteCond %{REQUEST_URI} !^/index(.*)\n" .
                                                   "RewriteCond %{REQUEST_URI} !^/static(.*)\n" .
                                                   "RewriteCond %{REQUEST_URI} !^/upload(.*)\n" .
                                                   "RewriteCond %{REQUEST_URI} !^/admin(.*)\n" .
                                                   "RewriteRule ^.*$ index.php [NC,L]");
        }
    }
