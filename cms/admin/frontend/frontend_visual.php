<?php

	
	defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/data/article.php";
    require_once CMS_ROOT . "core/data/page.php";
    require_once CMS_ROOT . "view/views/visual.php";
    require_once CMS_ROOT . "database/dao/link_dao.php";
    require_once CMS_ROOT . "database/dao/page_dao.php";
    require_once CMS_ROOT . "database/dao/article_dao.php";

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

        protected function getImageUrl($image) {
            return "/index.php?image=" . $image->getId();
        }

        protected function getArticleUrl($article) {
            $target_page = $article->getTargetPage();
            if (is_null($target_page))
                $target_page = $this->_current_page;
            return "/index.php?id=" . $target_page->getId() . "&amp;articleid=" . $article->getId();
        }

        protected function getPageUrl($page) {
            return "/index.php?id=" . $page->getId();
        }

        private function createLinksInString($value, $element_holder) {
            $links = $this->_link_dao->getLinksForElementHolder($element_holder->getId());
            foreach ($links as $link) {
                if ($this->containsLink($value, $link)) {
                    if (!is_null($link->getTargetElementHolderId()))
                        $url = $this->createUrlFromLink($link);
                    else
                        $url = $link->getTargetAddress();
                    $value = $this->replaceLinkCodeTags($value, $link, $url);
                }
            }
            return $value;
        }

        private function replaceLinkCodeTags($value, $link, $url) {
            $value = str_replace($this->getLinkCodeOpeningTag($link), $this->createHyperlinkOpeningTag($link->getTitle(), $url), $value);
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

        private function createHyperlinkOpeningTag($title, $url) {
            return "<a title=\"" . $title . "\" href=\"" . $url . "\">";
        }
	}

?>