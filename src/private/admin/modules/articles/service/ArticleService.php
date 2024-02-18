<?php
require_once CMS_ROOT . "/modules/articles/model/Article.php";

interface ArticleService {
    public function updateArticle(Article $article): void;

    public function getAllChildArticlesFor(Article $article): array;

    public function createArticle(): Article;

    public function deleteArticle(Article $article): void;

    public function getTermsForArticle(Article $article): array;

    public function addTermToArticle(int $termId, Article $article): void;

    public function deleteTermFromArticle(int $termId, Article $article): void;

    public function getArticle(int $id): Article;

    public function deleteTargetPage(Page $page): void;

    public function setDefaultArticleTargetPage(int $pageId): void;

    public function addTargetPage(int $pageId): void;
}