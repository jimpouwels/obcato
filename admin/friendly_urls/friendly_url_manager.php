<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'database/dao/friendly_url_dao.php';
    require_once CMS_ROOT . 'database/dao/settings_dao.php';
    require_once CMS_ROOT . 'database/dao/page_dao.php';
    require_once CMS_ROOT . 'database/dao/article_dao.php';

    class FriendlyUrlManager {

        private static $instance;
        private $_friendly_url_dao;
        private $_settings_dao;
        private $_page_dao;
        private $_article_dao;

        public function __construct() {
            $this->_friendly_url_dao = FriendlyUrlDao::getInstance();
            $this->_settings_dao = SettingsDao::getInstance();
            $this->_page_dao = PageDao::getInstance();
            $this->_article_dao = ArticleDao::getInstance();
            $this->writeHtaccessFileIfNotExists();
        }
        
        public static function getInstance() {
            if (!self::$instance) {
                self::$instance = new FriendlyUrlManager();
            }
            return self::$instance;
        }

        public function insertOrUpdateFriendlyUrlForPage($page) {
            $url = '/' . $this->createUrlForPage($page);
            $this->insertOrUpateFriendlyUrl($url, $page);
        }

        public function insertOrUpdateFriendlyUrlForArticle($article) {
            $url = '/' . $this->createUrlForArticle($article);
            $this->insertOrUpateFriendlyUrl($url, $article);
        }

        public function getPageFromUrl($url) {
            $url = UrlHelper::removeQueryStringFrom($url);
            $element_holder_id = $this->_friendly_url_dao->getElementHolderIdFromUrl($url);
            $page = $this->_page_dao->getPageByElementHolderId($element_holder_id);
            if (is_null($page)) {
                $page_part_of_url = UrlHelper::removeLastPartFromUrl($url);
                $element_holder_id = $this->_friendly_url_dao->getElementHolderIdFromUrl($page_part_of_url);
                $page = $this->_page_dao->getPageByElementHolderId($element_holder_id);
            }
            return $page;
        }

        public function getArticleFromUrl($url): ?Article {
            $url_parts = UrlHelper::splitIntoParts($url);
            if (count($url_parts) > 1) {
                $last_url_part = $url_parts[count($url_parts) - 1];
                $element_holder_id = $this->_friendly_url_dao->getElementHolderIdFromUrl('/' . $last_url_part);
                try {
                    return $this->_article_dao->getArticleByElementHolderId($element_holder_id);
                } catch (DaoException $e) {
                    return null;
                }
            }
        }

        public function getFriendlyUrlForElementHolder($element_holder) {
            return $this->_friendly_url_dao->getUrlFromElementHolder($element_holder);
        }

        private function insertOrUpateFriendlyUrl($url, $element_holder) {
            $url = $this->appendNumberIfFriendlyUrlExists($url, $element_holder);
            if (is_null($this->getFriendlyUrlForElementHolder($element_holder)))
                $this->_friendly_url_dao->insertFriendlyUrl($url, $element_holder);
            else
                $this->_friendly_url_dao->updateFriendlyUrl($url, $element_holder);
        }

        private function createUrlForPage($page) {
            $url = $this->replaceSpecialCharacters($page->getNavigationTitle());
            $parent_page = $page->getParent();
            if ($parent_page != null && $parent_page->getId() != $this->_page_dao->getRootPage()->getId())
                $url = $this->createUrlForPage($page->getParent()) . "/" . $url;
            return $url;
        }

        private function createUrlForArticle($article) {
            return $this->replaceSpecialCharacters($article->getTitle());
        }

        private function appendNumberIfFriendlyUrlExists($url, $element_holder) {
            $new_url = $url;
            $existing_element_holder_id = $this->_friendly_url_dao->getElementHolderIdFromUrl($url);
            $number = 1;
            while ($existing_element_holder_id != null && $existing_element_holder_id != $element_holder->getId()) {
                $new_url = $url . $number;
                $number++;
                $existing_element_holder_id = $this->_friendly_url_dao->getElementHolderIdFromUrl($new_url);
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
