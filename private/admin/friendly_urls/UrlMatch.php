<?php

class UrlMatch {

    private ?Page $_page = null;
    private ?string $_page_url = null;
    private ?Article $_article = null;
    private ?string $_article_url = null;

    public function getPage(): ?Page {
        return $this->_page;
    }

    public function setPage(?Page $page, ?string $url): void {
        $this->_page = $page;
        $this->_page_url = $url;
    }

    public function getArticle(): ?Article {
        return $this->_article;
    }

    public function setArticle(?Article $article, ?string $url): void {
        $this->_article = $article;
        $this->_article_url = $url;
    }

    public function getPageUrl(): string {
        return $this->_page_url;
    }

    public function getArticleUrl(): string {
        return $this->_article_url;
    }

}
