<?php

require_once(CMS_ROOT . '/database/dao/ArticleDao.php');

class ArticleDaoMock implements ArticleDao {

    private array $articles = array();

    public function getArticle($id): ?Article {
        // TODO: Implement getArticle() method.
    }

    public function getArticleByElementHolderId($elementHolderId): ?Article {
        return Arrays::firstMatch($this->articles, function ($article) use ($elementHolderId) {
            return $article->getId() == $elementHolderId;
        });
    }

    public function getAllArticles(): array {
        // TODO: Implement getAllArticles() method.
    }

    public function getAllChildArticles(int $parentArticleId): array {
        // TODO: Implement getAllChildArticles() method.
    }

    public function searchArticles(string $keyword, int $termId): array {
        // TODO: Implement searchArticles() method.
    }

    public function searchPublishedArticles(?string $fromDate, ?string $toDate, ?string $orderBy, ?string $orderType, ?array $terms, ?int $maxResults): array {
        // TODO: Implement searchPublishedArticles() method.
    }

    public function updateArticle(Article $article): void {
        // TODO: Implement updateArticle() method.
    }

    public function deleteArticle($article): void {
        // TODO: Implement deleteArticle() method.
    }

    public function createArticle(Article $article): void {
        // TODO: Implement createArticle() method.
    }

    public function getArticleComments(int $articleId): array {
        // TODO: Implement getArticleComments() method.
    }

    public function getChildArticleComments(int $commentId): array {
        // TODO: Implement getChildArticleComments() method.
    }

    public function getAllTerms(): array {
        // TODO: Implement getAllTerms() method.
    }

    public function getTerm($id): ?ArticleTerm {
        // TODO: Implement getTerm() method.
    }

    public function createTerm($termName): ArticleTerm {
        // TODO: Implement createTerm() method.
    }

    public function getTermByName($name): ?ArticleTerm {
        // TODO: Implement getTermByName() method.
    }

    public function updateTerm($term): void {
        // TODO: Implement updateTerm() method.
    }

    public function deleteTerm($term): void {
        // TODO: Implement deleteTerm() method.
    }

    public function getTermsForArticle(int $articleId): array {
        // TODO: Implement getTermsForArticle() method.
    }

    public function addTermToArticle($termId, $article): void {
        // TODO: Implement addTermToArticle() method.
    }

    public function deleteTermFromArticle($termId, $article): void {
        // TODO: Implement deleteTermFromArticle() method.
    }

    public function addTargetPage($targetPageId): void {
        // TODO: Implement addTargetPage() method.
    }

    public function getTargetPages(): array {
        // TODO: Implement getTargetPages() method.
    }

    public function deleteTargetPage($targetPageId): void {
        // TODO: Implement deleteTargetPage() method.
    }

    public function getDefaultTargetPage(): ?Page {
        // TODO: Implement getDefaultTargetPage() method.
    }

    public function setDefaultArticleTargetPage($targetPageId): void {
        // TODO: Implement setDefaultArticleTargetPage() method.
    }

    public function addArticle(Article $article): void {
        $this->articles[] = $article;
    }
}