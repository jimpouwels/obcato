<?php

namespace Pageflow\Core\frontend\helper;

use Pageflow\Core\core\model\Link;
use Pageflow\Core\friendly_urls\FriendlyUrlManager;
use Pageflow\Core\modules\articles\model\Article;
use Pageflow\Core\modules\articles\service\ArticleInteractor;
use Pageflow\Core\modules\articles\service\ArticleService;
use Pageflow\Core\modules\images\model\Image;
use Pageflow\Core\modules\pages\model\Page;
use Pageflow\Core\modules\pages\service\PageInteractor;
use Pageflow\Core\modules\pages\service\PageService;
use Pageflow\Core\utilities\UrlHelper;

class LinkHelper
{
    private static ?LinkHelper $instance = null;
    private FriendlyUrlManager $friendlyUrlManager;
    private PageService $pageService;
    private ArticleService $articleService;
    private ?Page $currentPage;
    private ?Article $currentArticle;

    private function __construct(?Page $currentPage, ?Article $currentArticle) {
        $this->friendlyUrlManager = FriendlyUrlManager::getInstance();
        $this->pageService = PageInteractor::getInstance();
        $this->articleService = ArticleInteractor::getInstance();
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
        return $this->createImageUrl($image) . '?mobile=true';
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
        if (FrontendHelper::isPreviewMode()) {
            $url = FrontendHelper::asPreviewUrl($url);
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

    public function createUrlFromLink(Link $link): ?string {
        if (!$link->getTargetElementHolderId()) {
            return $link->getTargetAddress() ?: null;
        }
        
        $targetElementHolder = $link->getTargetElementHolder();
        switch ($targetElementHolder->getType()) {
            case Page::ElementHolderType:
                $targetPage = $this->pageService->getPageById($targetElementHolder->getId());
                if (!$targetPage?->isPublished()) {
                    return null;
                }
                return $this->createPageUrl($targetPage);
            case Article::ElementHolderType:
                $targetArticle = $this->articleService->getArticle($targetElementHolder->getId());
                if (!$targetArticle?->isPublished()) {
                    return null;
                }
                $url = $this->createArticleUrl($targetArticle);
                break;
            default:
                return null;
        }
        
        if (FrontendHelper::isPreviewMode() && $link->getTargetElementHolderId()) {
            $url = FrontendHelper::asPreviewUrl($url);
        }
        return $url;
    }
    
    public function processRichTextLinks(string $html): string {
        return preg_replace_callback('/<a\s+([^>]*)>(.*?)<\/a>/is', function($matches) {
            $dataAttrs = $matches[1];
            $linkText = $matches[2];
            
            // Extract data-link-* attributes
            $linkType = preg_match('/data-link-type="([^"]+)"/i', $dataAttrs, $m) ? $m[1] : null;
            $linkId = preg_match('/data-link-id="([^"]+)"/i', $dataAttrs, $m) ? (int)$m[1] : null;
            $href = preg_match('/data-link-url="([^"]+)"/i', $dataAttrs, $m) ? htmlspecialchars_decode($m[1], ENT_QUOTES) : null;
            $linkTarget = preg_match('/data-link-target="([^"]+)"/i', $dataAttrs, $m) ? $m[1] : null;
            
            
            if ($linkType === 'page' && $linkId) {
                $page = $this->pageService->getPageById($linkId);
                if (!$page || !$page->isPublished()) {
                    return $linkText;
                } else {
                    $href = $this->createPageUrl($page);
                }
            } elseif ($linkType === 'article' && $linkId) {
                $article = $this->articleService->getArticle($linkId);
                if (!$article || !$article->isPublished()) {
                    return $linkText;
                } else {
                    $href = $this->createArticleUrl($article);
                }
            }
            
            $attrs = ' href="' . htmlspecialchars($href, ENT_QUOTES) . '"';
            if ($linkTarget === 'external') {
                $attrs .= ' class="external" target="_blank"';
            }
            
            return '<a ' . trim($attrs) . '>' . $linkText . '</a>';
        }, $html);
    }

    private function processMarkdownStyleLinks(string $value): string {
        $matches = null;
        preg_match_all('/\[(.*?)]\((.*?)\)/', $value, $matches);
        for ($i = 0; $i < count($matches[0]); $i++) {
            $url = $matches[2][$i];
            
            if (!preg_match('/^https?:\/\//i', $url) && !preg_match('/^\//', $url)) {
                $url = 'https://' . $url;
            }
            
            $link = "<a target=\"_blank\" class=\"external\" href=\"{$url}\" title=\"{$matches[1][$i]}\" alt=\"{$matches[1][$i]}\">{$matches[1][$i]}</a>";
            $value = str_replace($matches[0][$i], $link, $value);
        }
        return $value;
    }
}