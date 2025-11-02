<?php

namespace Obcato\Core\frontend\helper;

use Obcato\Core\core\model\ElementHolder;
use Obcato\Core\core\model\Link;
use Obcato\Core\database\dao\LinkDao;
use Obcato\Core\database\dao\LinkDaoMysql;
use Obcato\Core\friendly_urls\FriendlyUrlManager;
use Obcato\Core\modules\articles\model\Article;
use Obcato\Core\modules\articles\service\ArticleInteractor;
use Obcato\Core\modules\articles\service\ArticleService;
use Obcato\Core\modules\images\model\Image;
use Obcato\Core\modules\pages\model\Page;
use Obcato\Core\modules\pages\service\PageInteractor;
use Obcato\Core\modules\pages\service\PageService;
use Obcato\Core\utilities\UrlHelper;

class LinkHelper
{
    private static ?LinkHelper $instance = null;
    private FriendlyUrlManager $friendlyUrlManager;
    private PageService $pageService;
    private ArticleService $articleService;
    private LinkDao $linkDao;
    private ?Page $currentPage;
    private ?Article $currentArticle;

    private function __construct(?Page $currentPage, ?Article $currentArticle) {
        $this->friendlyUrlManager = FriendlyUrlManager::getInstance();
        $this->pageService = PageInteractor::getInstance();
        $this->articleService = ArticleInteractor::getInstance();
        $this->linkDao = LinkDaoMysql::getInstance();
        $this->currentPage = $currentPage;
        $this->currentArticle = $currentArticle;
    }

    public static function getInstance(?Page $currentPage, ?Article $currentArticle): LinkHelper {
        if (!self::$instance) {
            self::$instance = new LinkHelper($currentPage, $currentArticle);
        }
        return self::$instance;
    }

    public function createPageUrl(Page $page, bool $absolute = false): string {
        $url = $absolute ? $this->createBaseUrl() : "";
        if ($page->isHomepage()) {
            return "$url" . ($absolute ? "" : "/");
        }
        $url = $absolute ? $this->createBaseUrl() : "";
        $friendlyUrl = $this->friendlyUrlManager->getFriendlyUrlForElementHolder($page);
        if (!$friendlyUrl) {
            $url .= '/index.php?id=' . $page->getId();
        } else {
            $url .= $friendlyUrl;
        }
        return $url;
    }

    public function createImageUrl(?Image $image): string {
        if (!$image) {
            return "";
        }
        return "/image/" . $image->getId();
    }

    public function createMobileImageUrl(?Image $image): string {
        if (!$image) {
            return "";
        }
        return $this->createImageUrl($image) . '&mobile=true';
    }

    public function createArticleUrl(Article $article, bool $absolute = false): string {
        $targetPage = $this->pageService->getPageById($article->getTargetPageId());
        if (!$targetPage) {
            $targetPage = $this->currentPage;
        }
        $url = $absolute ? $this->createBaseUrl() : "";
        if ($targetPage) {
            $url .= $this->createPageUrl($targetPage);
        }
        $friendlyUrl = $this->friendlyUrlManager->getFriendlyUrlForElementHolder($article);
        if (!$friendlyUrl) {
            $url .= UrlHelper::addQueryStringParameter($url, 'articleid', $article->getId());
        } else {
            $url .= $friendlyUrl;
        }
        return $url;
    }

    public function createCanonicalUrl(): string {
        if ($this->currentArticle) {
            return $this->createArticleUrl($this->currentArticle, true);
        } else {
            return $this->createPageUrl($this->currentPage, true);
        }
    }

    public function createBaseUrl(): string {
        $baseUrl = 'https://';
        $baseUrl .= $_SERVER['HTTP_HOST'];
        return $baseUrl;
    }

    public function createLinksInString(string $value, ElementHolder $elementHolder): string {
        $links = $this->linkDao->getLinksForElementHolder($elementHolder->getId());
        foreach ($links as $link) {
            if ($this->containsLink($value, $link)) {
                $url = $this->createUrlFromLink($link);
                $value = $this->replaceLinkCodeTags($value, $link, $url);
            }
        }
        return $this->processMarkdownStyleLinks($value);
    }

    public function createUrlFromLink(Link $link): string {
        $url = "";
        if (!$link->getTargetElementHolderId()) {
            $url = $link->getTargetAddress();
        } else {
            $targetElementHolder = $link->getTargetElementHolder();
            switch ($targetElementHolder->getType()) {
                case Page::ElementHolderType:
                    $targetPage = $this->pageService->getPageById($targetElementHolder->getId());
                    $url = $this->createPageUrl($targetPage);
                    break;
                case Article::ElementHolderType:
                    $targetArticle = $this->articleService->getArticle($targetElementHolder->getId());
                    $url = $this->createArticleUrl($targetArticle);
                    break;
                default:
                    return "";
            }
        }
        if (FrontendHelper::isPreviewMode() && $link->getTargetElementHolderId()) {
            $url = FrontendHelper::asPreviewUrl($url);
        }
        return $url;
    }

    private function processMarkdownStyleLinks(string $value): string {
        $matches = null;
        preg_match_all('/\[(.*?)]\((.*?)\)/', $value, $matches);
        for ($i = 0; $i < count($matches[0]); $i++) {
            $link = "<a target=\"_blank\" class=\"external\" href=\"{$matches[2][$i]}\" title=\"{$matches[1][$i]}\" alt=\"{$matches[1][$i]}\">{$matches[1][$i]}</a>";
            $value = str_replace($matches[0][$i], $link, $value);
        }
        return $value;
    }

    private function replaceLinkCodeTags(string $value, Link $link, string $url): string {
        $linkClass = $link->getTargetElementHolderId() ? "internal" : "external";
        $value = str_replace($this->getLinkCodeOpeningTag($link), $this->createHyperlinkOpeningTag($link->getTitle(), $link->getTarget(), $url, $linkClass), $value);
        return str_replace("[/LINK]", "</a>", $value);
    }

    private function containsLink(string $value, Link $link): bool {
        return strpos($value, $this->getLinkCodeOpeningTag($link)) > -1;
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