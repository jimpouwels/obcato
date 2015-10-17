<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'database/dao/friendly_url_dao.php';
    require_once CMS_ROOT . 'database/dao/settings_dao.php';

    class FriendlyUrlManager {

        private $_friendly_url_dao;
        private $_settings_dao;

        public function __construct() {
            $this->_friendly_url_dao = FriendlyUrlDao::getInstance();
            $this->_settings_dao = SettingsDao::getInstance();
            $this->writeHtaccessFileIfNotExists();
        }

        public function addFriendlyUrlForPage($page) {
            $target_url = $this->toFriendlyUrlTitle($page->getNavigationTitle());
            $this->_friendly_url_dao->insertOrUpdateFriendlyUrl($target_url, $page);
        }

        public function getPageFromUrl($url) {
            return $this->_friendly_url_dao->getPageFromUrl($url);
        }

        public function getFriendlyUrlForPage($page) {
            return $this->_friendly_url_dao->getUrlFromPage($page);
        }

        private function toFriendlyUrlTitle($value) {
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
