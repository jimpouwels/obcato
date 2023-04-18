<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/article.php";
    require_once CMS_ROOT . "core/model/page.php";
    require_once CMS_ROOT . "database/dao/link_dao.php";
    require_once CMS_ROOT . "database/dao/page_dao.php";
    require_once CMS_ROOT . "database/dao/article_dao.php";
    require_once CMS_ROOT . 'friendly_urls/friendly_url_manager.php';
    require_once CMS_ROOT . "view/template_engine.php";
    require_once CMS_ROOT . 'utilities/url_helper.php';

    abstract class FrontendVisual {

        private Smarty $_template_engine;
        private LinkDao $_link_dao;
        private PageDao $_page_dao;
        private ArticleDao $_article_dao;
        private FriendlyUrlManager $_friendly_url_manager;
        private Page $_page;

        public function __construct(Page $page) {
            $this->_link_dao = LinkDao::getInstance();
            $this->_page_dao = PageDao::getInstance();
            $this->_article_dao = ArticleDao::getInstance();
            $this->_page = $page;
            $this->_template_engine = TemplateEngine::getInstance();
            $this->_friendly_url_manager = FriendlyUrlManager::getInstance();
        }

        public abstract function render(): string;

        protected function toHtml(string $value, ElementHolder $element_holder): string {
            $value = nl2br($value);
            return $this->createLinksInString($value, $element_holder);
        }

        protected function getImageUrl(Image $image): string {
            return $this->getPageUrl($this->_page) . '?image=' . $image->getId();
        }

        protected function getPage(): ElementHolder {
            return $this->_page;
        }

        protected function getTemplateEngine(): Smarty {
            return $this->_template_engine;
        }

        protected function getArticleUrl(Article $article): string {
            $target_page = $article->getTargetPage();
            if (is_null($target_page)) {
                $target_page = $this->_page;
            }
            $url = $this->_friendly_url_manager->getFriendlyUrlForElementHolder($article);
            if ($url == null) {
                $url = UrlHelper::addQueryStringParameter($this->getPageUrl($target_page), 'articleid', $article->getId());
            } else {
                $url = $this->getPageUrl($target_page) . $url;
            }
            return $url;
        }

        protected function getPageUrl(Page $page): string {
            $url = $this->_friendly_url_manager->getFriendlyUrlForElementHolder($page);
            if ($url == null) {
                $url = '/index.php?id=' . $page->getId();
            }
            return $url;
        }

        protected function toAnchorValue(string $value): string {
            $anchor_value = strtolower($value);
            $anchor_value = str_replace("-", " ", $anchor_value);
            $anchor_value = str_replace("  ", " ", $anchor_value);
            $anchor_value = str_replace(" ", "-", $anchor_value);
            $anchor_value = str_replace("--", "-", $anchor_value);
            return urlencode($anchor_value);
        }

        private function createLinksInString(string $value, ElementHolder $element_holder): string {
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

        private function replaceLinkCodeTags(string $value, Link $link, string $url): string {
            $value = str_replace($this->getLinkCodeOpeningTag($link), $this->createHyperlinkOpeningTag($link->getTitle(), $link->getTarget(), $url), $value);
            $value = str_replace("[/LINK]", "</a>", $value);
            return $value;
        }

        private function containsLink(string $value, Link $link): bool {
            return strpos($value, $this->getLinkCodeOpeningTag($link)) > -1;
        }

        private function createUrlFromLink(Link $link): string {
            $url = null;
            $target_element_holder = $link->getTargetElementHolder();
            switch ($target_element_holder->getType()) {
                case Page::ElementHolderType:
                    $target_page = $this->_page_dao->getPage($target_element_holder->getId());
                    return $this->getPageUrl($target_page);
                case Article::ElementHolderType:
                    $target_article = $this->_article_dao->getArticle($target_element_holder->getId());
                    return $this->getArticleUrl($target_article);
                default:
                    return "";
            }
        }

        private function getLinkCodeOpeningTag(Link $link): string {
            return "[LINK C=\"" . $link->getCode() . "\"]";
        }

        private function createHyperlinkOpeningTag(string $title, string $target, string $url): string {
            if ($target == '[popup]') {
                $target_html = "onclick=\"window.open('$url','$title', 'width=800,height=600, scrollbars=no,toolbar=no,location=no'); return false\"";
            } else {
                $target_html = "target=\"$target\"";
            }
            return '<a title="' . $title . '" ' . $target_html . ' href="' . $url . '">';
        }
    }

?>
