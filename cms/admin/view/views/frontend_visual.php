<?php

	// No direct access
	defined('_ACCESS') or die;

    require_once CMS_ROOT . "/view/views/visual.php";
    require_once CMS_ROOT . "/database/dao/link_dao.php";
    require_once CMS_ROOT . "/database/dao/page_dao.php";
    require_once CMS_ROOT . "/database/dao/article_dao.php";

	abstract class FrontendVisual extends Visual {

        private $_link_dao;
        private $_page_dao;
        private $_article_dao;

        public function __construct($current_page) {
            $this->_link_dao = LinkDao::getInstance();
            $this->_page_dao = PageDao::getInstance();
            $this->_article_dao = ArticleDao::getInstance();
            $this->_current_page = $current_page;
        }

		protected function toHtml($value, $element_holder) {
            $value = nl2br($value);
            return $this->createLinksInString($value, $element_holder);
        }

        private function createLinksInString($value, $element_holder) {
            $links = $this->_link_dao->getLinksForElementHolder($element_holder->getId());
            $value = str_replace("[/LINK]", "</a>", $value);
            foreach ($links as $link) {
                if ($this->containsLink($value, $link)) {
                    if (!is_null($link->getTargetElementHolderId()))
                        $url = $this->createUrlFromLink($link);
                    else
                        $url = $link->getTargetAddress();
                    $value = str_replace($this->getOpeningTag($link), $this->createLinkTag($link->getTitle(), $url), $value);
                }
            }
            return $value;
        }

        private function createLinkTag($title, $url) {
            return "<a title=\"" . $title . "\" href=\"" . $url . "\">";
        }

        private function containsLink($value, $link) {
            $pos = strpos($value, $this->getOpeningTag($link));
            return $pos > 0;
        }

        private function getOpeningTag($link) {
            return "[LINK C=\"" . $link->getCode() . "\"]";
        }

        private function createUrlFromLink($link) {
            $url = null;
            $target_element_holder = $link->getTargetElementHolder();
            $target_element_holder_type = $target_element_holder->getType();
            switch ($target_element_holder_type) {
                case "ELEMENT_HOLDER_PAGE":
                    $target_page = $this->_page_dao->getPage($target_element_holder->getId());
                    return $this->getPageFrontendUrl($target_page);
                    break;
                case "ELEMENT_HOLDER_ARTICLE":
                    $target_article = $this->_article_dao->getArticle($target_element_holder->getId());
                    return $this->getArticleFrontendUrl($target_article);
                    break;
            }
        }

        private function getPageFrontendUrl($page) {
            return "/index.php?id=" . $page->getId();
        }

        private function getArticleFrontendUrl($article) {
            $target_page = $article->getTargetPage();
            if (is_null($target_page))
                $target_page = $this->_current_page;
            return "/index.php?id=" . $target_page->getId() . "&amp;articleid=" . $article->getId();
        }
	}

?>