<?php

namespace Obcato\Core\admin\friendly_urls;

class UrlMatch {

    private ?Page $page = null;
    private ?string $pageUrl = null;
    private ?Article $article = null;
    private ?string $articleUrl = null;

    public function getPage(): ?Page {
        return $this->page;
    }

    public function setPage(?Page $page, ?string $url): void {
        $this->page = $page;
        $this->pageUrl = $url;
    }

    public function getArticle(): ?Article {
        return $this->article;
    }

    public function setArticle(?Article $article, ?string $url): void {
        $this->article = $article;
        $this->articleUrl = $url;
    }

    public function getPageUrl(): string {
        return $this->pageUrl;
    }

    public function getArticleUrl(): string {
        return $this->articleUrl;
    }

}
