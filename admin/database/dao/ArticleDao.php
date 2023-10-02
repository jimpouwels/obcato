<?php
defined('_ACCESS') or die;

interface ArticleDao {
    public function getArticle($id): ?Article;

    public function getArticleByElementHolderId($element_holder_id): ?Article;

    public function getAllArticles(): array;

    public function getAllChildArticles(int $parent_article_id): array;

    public function searchArticles($keyword, $term_id): array;

    public function searchPublishedArticles($from_date, $to_date, $order_by, $order_type, $terms, $max_results): array;

    public function updateArticle(Article $article): void;

    public function deleteArticle($article): void;

    public function createArticle(): Article;

    public function getArticleComments(int $article_id): array;

    public function getChildArticleComments(int $comment_id): array;

    public function getAllTerms(): array;

    public function getTerm($id): ?ArticleTerm;

    public function createTerm($term_name): ArticleTerm;

    public function getTermByName($name): ?ArticleTerm;

    public function updateTerm($term): void;

    public function deleteTerm($term): void;

    public function getTermsForArticle($article_id): array;

    public function addTermToArticle($term_id, $article): void;

    public function deleteTermFromArticle($term_id, $article): void;

    public function addTargetPage($target_page_id): void;

    public function getTargetPages(): array;

    public function deleteTargetPage($target_page_id): void;

    public function getDefaultTargetPage(): ?Page;

    public function setDefaultArticleTargetPage($target_page_id): void;
}