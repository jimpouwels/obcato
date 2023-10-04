<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/modules/articles/model/Article.php";
require_once CMS_ROOT . "/modules/pages/model/Page.php";
require_once CMS_ROOT . "/database/dao/LinkDaoMysql.php";
require_once CMS_ROOT . "/database/dao/PageDaoMysql.php";
require_once CMS_ROOT . "/database/dao/TemplateDaoMysql.php";
require_once CMS_ROOT . "/database/dao/ArticleDaoMysql.php";
require_once CMS_ROOT . '/friendly_urls/FriendlyUrlManager.php';

abstract class FrontendVisual {

    private TemplateEngine $_template_engine;
    private Smarty_Internal_Data $_template_data;
    private LinkDao $_link_dao;
    private PageDao $_page_dao;
    private ArticleDao $_article_dao;
    private FriendlyUrlManager $_friendly_url_manager;
    private TemplateDao $_template_dao;
    private ?Page $_page;
    private ?Article $_article;

    public function __construct(?Page $page, ?Article $article) {
        $this->_link_dao = LinkDaoMysql::getInstance();
        $this->_page_dao = PageDaoMysql::getInstance();
        $this->_template_dao = TemplateDaoMysql::getInstance();
        $this->_article_dao = ArticleDaoMysql::getInstance();
        $this->_page = $page;
        $this->_article = $article;
        $this->_template_engine = TemplateEngine::getInstance();
        $this->_template_data = $this->_template_engine->createChildData();
        $this->_friendly_url_manager = FriendlyUrlManager::getInstance();
    }

    public function render(array &$parent_data = null): string {
        $this->load($parent_data);
        return $this->_template_engine->fetch($this->getTemplateFilename(), $this->_template_data);
    }

    public function load(?array &$parent_data): void {
        $presentable = $this->getPresentable();
        $template_vars = array();

        if ($presentable) {
            foreach ($presentable->getTemplate()->getTemplateVars() as $template_var) {
                $var_value = $template_var->getValue();
                if (empty($var_value)) {
                    $var_value = $this->_template_dao->getTemplateFile($presentable->getTemplate()->getTemplateFileId())->getTemplateVarDef($template_var->getName())->getDefaultValue();
                }
                $template_vars[$template_var->getName()] = $var_value;
            }
        }
        $this->assign("var", $template_vars);
        $this->loadVisual($parent_data);
    }

    abstract function loadVisual(?array &$data): void;

    abstract function getPresentable(): ?Presentable;

    abstract function getTemplateFilename(): string;

    protected function getTemplateEngine(): TemplateEngine {
        return $this->_template_engine;
    }

    protected function assign(string $key, mixed $value): void {
        $this->_template_data->assign($key, $value);
    }

    protected function assignGlobal(string $key, mixed $value): void {
        $this->_template_engine->assign($key, $value);
    }

    protected function fetch(string $template_filename): string {
        return $this->_template_engine->fetch($template_filename, $this->_template_data);
    }

    protected function toHtml(?string $value, ElementHolder $element_holder): string {
        if (!$value) {
            return "";
        }
        $value = nl2br($value);
        return $this->createLinksInString($value, $element_holder);
    }

    protected function getImageUrl(Image $image): string {
        return $this->getPageUrl($this->_page) . '?image=' . $image->getId();
    }

    protected function getPage(): Page {
        return $this->_page;
    }

    protected function getArticle(): ?Article {
        return $this->_article;
    }

    protected function getElementHolder(): ElementHolder {
        if ($this->_article) {
            return $this->_article;
        } else {
            return $this->_page;
        }
    }

    protected function createChildData(bool $include_current_data = false): Smarty_Internal_Data {
        if ($include_current_data) {
            return $this->_template_engine->createChildData($this->_template_data);
        } else {
            return $this->_template_engine->createChildData();
        }
    }

    protected function getArticleUrl(Article $article, bool $absolute = false): string {
        $target_page = $this->_page_dao->getPage($article->getTargetPageId());
        if (!$target_page) {
            $target_page = $this->_page;
        }
        $url = $absolute ? $this->getBaseUrl() : "";
        if ($target_page) {
            $url .= $this->getPageUrl($target_page);
        }
        $friendly_url = $this->_friendly_url_manager->getFriendlyUrlForElementHolder($article);
        if (!$friendly_url) {
            $url .= UrlHelper::addQueryStringParameter($url, 'articleid', $article->getId());
        } else {
            $url .= $friendly_url;
        }
        return $url;
    }

    protected function getPageUrl(Page $page, bool $absolute = false): string {
        $url = $absolute ? $this->getBaseUrl() : "";
        if ($page->isHomepage()) {
            return "${url}" . ($absolute ? "" : "/");
        }
        $url = $absolute ? $this->getBaseUrl() : "";
        $friendly_url = $this->_friendly_url_manager->getFriendlyUrlForElementHolder($page);
        if (!$friendly_url) {
            $url .= '/index.php?id=' . $page->getId();
        } else {
            $url .= $friendly_url;
        }
        return $url;
    }

    protected function getCanonicalUrl(): string {
        if ($this->getArticle()) {
            return $this->getArticleUrl($this->getArticle(), true);
        } else {
            return $this->getPageUrl($this->getPage(), true);
        }
    }

    protected function getBaseUrl(): string {
        $base_url = 'https://';
        $base_url .= str_replace('www.', '', $_SERVER['HTTP_HOST']);
        return $base_url;
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
        $link_class = $link->getTargetElementHolderId() ? "internal" : "external";
        $value = str_replace($this->getLinkCodeOpeningTag($link), $this->createHyperlinkOpeningTag($link->getTitle(), $link->getTarget(), $url, $link_class), $value);
        return str_replace("[/LINK]", "</a>", $value);
    }

    private function containsLink(string $value, Link $link): bool {
        return strpos($value, $this->getLinkCodeOpeningTag($link)) > -1;
    }

    private function createUrlFromLink(Link $link): string {
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

    private function createHyperlinkOpeningTag(string $title, string $target, string $url, string $link_class): string {
        if ($target == '[popup]') {
            $target_html = "onclick=\"window.open('$url','$title', 'width=800,height=600, scrollbars=no,toolbar=no,location=no'); return false\"";
        } else {
            $target_html = "target=\"$target\"";
        }
        return "<a title=\"{$title}\" {$target_html} href=\"{$url}\" class=\"{$link_class}\">";
    }
}

?>