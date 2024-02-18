<?php
require_once CMS_ROOT . "/modules/articles/model/Article.php";
require_once CMS_ROOT . "/modules/pages/model/Page.php";
require_once CMS_ROOT . "/database/dao/LinkDaoMysql.php";
require_once CMS_ROOT . "/modules/templates/service/TemplateInteractor.php";
require_once CMS_ROOT . "/modules/pages/service/PageInteractor.php";
require_once CMS_ROOT . "/database/dao/ArticleDaoMysql.php";
require_once CMS_ROOT . '/friendly_urls/FriendlyUrlManager.php';

abstract class FrontendVisual {

    private TemplateEngine $templateEngine;
    private Smarty_Internal_Data $templateData;
    private LinkDao $linkDao;
    private PageService $pageService;
    private ArticleDao $articleDao;
    private FriendlyUrlManager $friendlyUrlManager;
    private TemplateService $templateService;
    private ?Page $page;
    private ?Article $article;

    public function __construct(?Page $page, ?Article $article) {
        $this->linkDao = LinkDaoMysql::getInstance();
        $this->pageService = PageInteractor::getInstance();
        $this->templateService = TemplateInteractor::getInstance();
        $this->articleDao = ArticleDaoMysql::getInstance();
        $this->page = $page;
        $this->article = $article;
        $this->templateEngine = TemplateEngine::getInstance();
        $this->templateData = $this->createChildData();
        $this->friendlyUrlManager = FriendlyUrlManager::getInstance();
    }

    public function render(array &$parentData = null): string {
        $this->load($parentData);
        return $this->templateEngine->fetch($this->getTemplateFilename(), $this->templateData);
    }

    public function load(?array &$parentData): void {
        $presentable = $this->getPresentable();
        $templateVars = array();

        if ($presentable) {
            $templateVarDefs = $this->templateService->getTemplateVarDefsByTemplate($presentable->getTemplate());
            foreach ($presentable->getTemplate()->getTemplateVars() as $templateVar) {
                $varValue = $templateVar->getValue() ?: $this->getDefaultValueFor($templateVar, $templateVarDefs);
                if (is_numeric($varValue)) {
                    $varValue = intval($varValue);
                }
                $templateVars[$templateVar->getName()] = $varValue;
            }
        }
        $this->assign("var", $templateVars);
        $this->loadVisual($parentData);
    }

    abstract function loadVisual(?array &$data): void;

    abstract function getPresentable(): ?Presentable;

    abstract function getTemplateFilename(): string;

    protected function getTemplateEngine(): TemplateEngine {
        return $this->templateEngine;
    }

    protected function createChildData(): Smarty_Internal_Data {
        return $this->templateEngine->createChildData();
    }

    protected function assign(string $key, mixed $value): void {
        $this->templateData->assign($key, $value);
    }

    protected function assignGlobal(string $key, mixed $value): void {
        $this->templateEngine->assign($key, $value);
    }

    protected function fetch(string $templateFilename): string {
        return $this->templateEngine->fetch($templateFilename, $this->templateData);
    }

    protected function toHtml(?string $value, ElementHolder $elementHolder): string {
        if (!$value) {
            return "";
        }
        $value = nl2br($value);
        return $this->createLinksInString($value, $elementHolder);
    }

    protected function getImageUrl(Image $image): string {
        return $this->getPageUrl($this->page) . '?image=' . $image->getId();
    }

    protected function getPage(): Page {
        return $this->page;
    }

    protected function getArticle(): ?Article {
        return $this->article;
    }

    protected function getElementHolder(): ElementHolder {
        return !is_null($this->article) ? $this->article : $this->page;
    }

    protected function getArticleUrl(Article $article, bool $absolute = false): string {
        $targetPage = $this->pageService->getPageById($article->getTargetPageId());
        if (!$targetPage) {
            $targetPage = $this->page;
        }
        $url = $absolute ? $this->getBaseUrl() : "";
        if ($targetPage) {
            $url .= $this->getPageUrl($targetPage);
        }
        $friendlyUrl = $this->friendlyUrlManager->getFriendlyUrlForElementHolder($article);
        if (!$friendlyUrl) {
            $url .= UrlHelper::addQueryStringParameter($url, 'articleid', $article->getId());
        } else {
            $url .= $friendlyUrl;
        }
        return $url;
    }

    protected function getPageUrl(Page $page, bool $absolute = false): string {
        $url = $absolute ? $this->getBaseUrl() : "";
        if ($page->isHomepage()) {
            return "$url" . ($absolute ? "" : "/");
        }
        $url = $absolute ? $this->getBaseUrl() : "";
        $friendlyUrl = $this->friendlyUrlManager->getFriendlyUrlForElementHolder($page);
        if (!$friendlyUrl) {
            $url .= '/index.php?id=' . $page->getId();
        } else {
            $url .= $friendlyUrl;
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
        $baseUrl = 'https://';
        $baseUrl .= str_replace('www.', '', $_SERVER['HTTP_HOST']);
        return $baseUrl;
    }

    protected function toAnchorValue(string $value): string {
        $anchorValue = strtolower($value);
        $anchorValue = str_replace("-", " ", $anchorValue);
        $anchorValue = str_replace("  ", " ", $anchorValue);
        $anchorValue = str_replace(" ", "-", $anchorValue);
        $anchorValue = str_replace("--", "-", $anchorValue);
        return urlencode($anchorValue);
    }

    private function createLinksInString(string $value, ElementHolder $elementHolder): string {
        $links = $this->linkDao->getLinksForElementHolder($elementHolder->getId());
        foreach ($links as $link) {
            if ($this->containsLink($value, $link)) {
                $url = $this->createUrlFromLink($link);
                $value = $this->replaceLinkCodeTags($value, $link, $url);
            }
        }
        return $value;
    }

    private function getDefaultValueFor(TemplateVar $templateVar, array $templateVarDefs): string {
        return Arrays::firstMatch($templateVarDefs, fn($item) => $templateVar->getName() == $item->getName())->getDefaultValue();
    }

    private function replaceLinkCodeTags(string $value, Link $link, string $url): string {
        $linkClass = $link->getTargetElementHolderId() ? "internal" : "external";
        $value = str_replace($this->getLinkCodeOpeningTag($link), $this->createHyperlinkOpeningTag($link->getTitle(), $link->getTarget(), $url, $linkClass), $value);
        return str_replace("[/LINK]", "</a>", $value);
    }

    private function containsLink(string $value, Link $link): bool {
        return strpos($value, $this->getLinkCodeOpeningTag($link)) > -1;
    }

    private function createUrlFromLink(Link $link): string {
        if (!$link->getTargetElementHolderId()) {
            return $link->getTargetAddress();
        }
        $targetElementHolder = $link->getTargetElementHolder();
        switch ($targetElementHolder->getType()) {
            case Page::ElementHolderType:
                $targetPage = $this->pageService->getPageById($targetElementHolder->getId());
                return $this->getPageUrl($targetPage);
            case Article::ElementHolderType:
                $targetArticle = $this->articleDao->getArticle($targetElementHolder->getId());
                return $this->getArticleUrl($targetArticle);
            default:
                return "";
        }
    }

    private function getLinkCodeOpeningTag(Link $link): string {
        return "[LINK C=\"" . $link->getCode() . "\"]";
    }

    private function createHyperlinkOpeningTag(string $title, string $target, string $url, string $link_class): string {
        if ($target == '[popup]') {
            $targetHtml = "onclick=\"window.open('$url','$title', 'width=800,height=600, scrollbars=no,toolbar=no,location=no'); return false\"";
        } else {
            $targetHtml = "target=\"$target\"";
        }
        return "<a title=\"{$title}\" {$targetHtml} href=\"{$url}\" class=\"{$link_class}\">";
    }
}