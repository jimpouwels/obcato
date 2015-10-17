<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "frontend/friendly_urls/htaccess_writer.php";

    class FriendlyUrlManager {

        const DEFAULT_BASE_URL = 'index.php';
        const PAGE_ID_QUERYSTRING_PARAM = 'id';

        public function __construct() {
            $this->_htaccessWriter = new HtAccessWriter();
        }

        public function addFriendlyUrlForPage($page) {
            $target_url = $this->toFriendlyUrlTitle($page->getNavigationTitle());
            $source_url = self::DEFAULT_BASE_URL . '?' . self::PAGE_ID_QUERYSTRING_PARAM . '=' . $page->getId();
            $this->_htaccessWriter->addOrUpdateRewriteRule($page->getId(), $source_url, $target_url);
        }

        public function getFriendlyUrlForPage($page) {
            return $this->toFriendlyUrlTitle($page->getNavigationTitle());
        }

        private function toFriendlyUrlTitle($value) {
            $value = strtolower($value);
            $value = str_replace(' ', '-', $value);
            $value = preg_replace('/[^a-z-0-9]/', '', $value);
            return $value;
        }
    }
