<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/article.php";
    require_once CMS_ROOT . "core/model/page.php";
    require_once CMS_ROOT . "database/dao/link_dao.php";
    require_once CMS_ROOT . "database/dao/page_dao.php";
    require_once CMS_ROOT . "database/dao/article_dao.php";
    require_once CMS_ROOT . 'friendly_urls/friendly_url_manager.php';
    require_once CMS_ROOT . 'utilities/url_helper.php';

    abstract class FrontendVisual {

        private $_link_dao;
        private $_page_dao;
        private $_article_dao;
        private $_friendly_url_manager;
        private $_current_page;

        public function __construct($current_page) {
            $this->_link_dao = LinkDao::getInstance();
            $this->_page_dao = PageDao::getInstance();
            $this->_article_dao = ArticleDao::getInstance();
            $this->_current_page = $current_page;
            $this->_friendly_url_manager = FriendlyUrlManager::getInstance();
        }

        public abstract function render();

        protected function toHtml($value, $element_holder) {
            $value = nl2br($value);
            return $this->createLinksInString($value, $element_holder);
        }

        protected function getImageUrl($image) {
            return $this->getPageUrl($this->_current_page) . '?image=' . $image->getId();
        }

        protected function getPage(): Page {
            return $this->_current_page;
        }

        protected function getArticleUrl($article) {
            $target_page = $article->getTargetPage();
            if (is_null($target_page)) {
                $target_page = $this->_current_page;
            }
            $url = $this->_friendly_url_manager->getFriendlyUrlForElementHolder($article);
            if ($url == null) {
                $url = UrlHelper::addQueryStringParameter($this->getPageUrl($target_page), 'articleid', $article->getId());
            } else {
                $url = $this->getPageUrl($target_page) . $url;
            }
            return $url;
        }

        protected function getPageUrl($page) {
            $url = $this->_friendly_url_manager->getFriendlyUrlForElementHolder($page);
            if ($url == null) {
                $url = '/index.php?id=' . $page->getId();
            }
            return $url;
        }

        protected function toAnchorValue($value): string {
            $anchor_value = str_replace(" ", '-', strtolower($value));
            return urlencode($anchor_value);
        }

        private function createLinksInString($value, $element_holder) {
            $links = $this->_link_dao->getLinksForElementHolder($element_holder->getId());
            foreach ($links as $link) {
                if ($this->containsLink($value, $link)) {
                    if (!is_null($link->getTargetElementHolderId())) {
                        $url = $this->createUrlFromLink($link);
                    } else {
                        $url = $link->getTargetAddress();
                    }
                    $value = $this->replaceLinkCodeTags($value, $link, $url);
                }
            }
            return $value;
        }

        private function replaceLinkCodeTags($value, $link, $url) {
            $value = str_replace($this->getLinkCodeOpeningTag($link), $this->createHyperlinkOpeningTag($link->getTitle(), $link->getTarget(), $url), $value);
            $value = str_replace("[/LINK]", "</a>", $value);
            return $value;
        }

        private function containsLink($value, $link) {
            return strpos($value, $this->getLinkCodeOpeningTag($link)) > -1;
        }

        private function createUrlFromLink($link) {
            $url = null;
            $target_element_holder = $link->getTargetElementHolder();
            switch ($target_element_holder->getType()) {
                case Page::ElementHolderType:
                    $target_page = $this->_page_dao->getPage($target_element_holder->getId());
                    return $this->getPageUrl($target_page);
                    break;
                case Article::ElementHolderType:
                    $target_article = $this->_article_dao->getArticle($target_element_holder->getId());
                    return $this->getArticleUrl($target_article);
                    break;
            }
        }

        private function getLinkCodeOpeningTag($link) {
            return "[LINK C=\"" . $link->getCode() . "\"]";
        }

        private function createHyperlinkOpeningTag($title, $target, $url) {
            if ($target == '[popup]')
                $target_html = "onclick=\"window.open('$url','$title', 'width=800,height=600, scrollbars=no,toolbar=no,location=no'); return false\"";
            else
                $target_html = "target=\"$target\"";
            return '<a title="' . $title . '" ' . $target_html . ' href="' . $url . '">';
        }
    }

?>
