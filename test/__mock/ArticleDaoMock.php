<?php

require_once(CMS_ROOT . '/database/dao/ArticleDao.php');

class ArticleDaoMock implements ArticleDao {

    public function getArticle($id): ?Article {
        // TODO: Implement getArticle() method.
    }

    public function getArticleByElementHolderId($element_holder_id): ?Article {
        // TODO: Implement getArticleByElementHolderId() method.
    }

    public function getAllArticles(): array {
        // TODO: Implement getAllArticles() method.
    }

    public function getAllChildArticles(int $parent_article_id): array {
        // TODO: Implement getAllChildArticles() method.
    }

    public function searchArticles($keyword, $term_id): array {
        // TODO: Implement searchArticles() method.
    }

    public function searchPublishedArticles($from_date, $to_date, $order_by, $order_type, $terms, $max_results): array {
        // TODO: Implement searchPublishedArticles() method.
    }

    public function updateArticle(Article $article): void {
        // TODO: Implement updateArticle() method.
    }

    public function deleteArticle($article): void {
        // TODO: Implement deleteArticle() method.
    }

    public function createArticle(): Article {
        // TODO: Implement createArticle() method.
    }

    public function getArticleComments(int $article_id): array {
        // TODO: Implement getArticleComments() method.
    }

    public function getChildArticleComments(int $comment_id): array {
        // TODO: Implement getChildArticleComments() method.
    }

    public function getAllTerms(): array {
        // TODO: Implement getAllTerms() method.
    }

    public function getTerm($id): ?ArticleTerm {
        // TODO: Implement getTerm() method.
    }

    public function createTerm($term_name): ArticleTerm {
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

    public function getTermsForArticle($article_id): array {
        // TODO: Implement getTermsForArticle() method.
    }

    public function addTermToArticle($term_id, $article): void {
        // TODO: Implement addTermToArticle() method.
    }

    public function deleteTermFromArticle($term_id, $article): void {
        // TODO: Implement deleteTermFromArticle() method.
    }

    public function addTargetPage($target_page_id): void {
        // TODO: Implement addTargetPage() method.
    }

    public function getTargetPages(): array {
        // TODO: Implement getTargetPages() method.
    }

    public function deleteTargetPage($target_page_id): void {
        // TODO: Implement deleteTargetPage() method.
    }

    public function getDefaultTargetPage(): ?Page {
        // TODO: Implement getDefaultTargetPage() method.
    }

    public function setDefaultArticleTargetPage($target_page_id): void {
        // TODO: Implement setDefaultArticleTargetPage() method.
    }
}