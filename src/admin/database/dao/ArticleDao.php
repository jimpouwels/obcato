<?php

namespace Obcato\Core\admin\database\dao;

use Obcato\Core\admin\modules\articles\model\Article;
use Obcato\Core\admin\modules\articles\model\ArticleTerm;
use Obcato\Core\admin\modules\pages\model\Page;

interface ArticleDao {
    public function getArticle($id): ?Article;

    public function getArticleByElementHolderId($elementHolderId): ?Article;

    public function getAllArticles(): array;

    public function getAllChildArticles(int $parentArticleId): array;

    public function searchArticles(string $keyword, ?int $termId): array;

    public function searchPublishedArticles(?string $fromDate, ?string $toDate, ?string $orderBy, ?string $orderType, ?array $terms, ?int $maxResults): array;

    public function updateArticle(Article $article): void;

    public function deleteArticle($article): void;

    public function createArticle(Article $article): void;

    public function getArticleComments(int $articleId): array;

    public function getChildArticleComments(int $commentId): array;

    public function getAllTerms(): array;

    public function getTerm($id): ?ArticleTerm;

    public function createTerm($termName): ArticleTerm;

    public function getTermByName($name): ?ArticleTerm;

    public function updateTerm($term): void;

    public function deleteTerm($term): void;

    public function getTermsForArticle(int $articleId): array;

    public function addTermToArticle($termId, $article): void;

    public function deleteTermFromArticle($termId, $article): void;

    public function addTargetPage($targetPageId): void;

    public function getTargetPages(): array;

    public function deleteTargetPage($targetPageId): void;

    public function getDefaultTargetPage(): ?Page;

    public function setDefaultArticleTargetPage($targetPageId): void;
}