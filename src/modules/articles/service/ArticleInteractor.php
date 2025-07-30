<?php

namespace Obcato\Core\modules\articles\service;

use Obcato\Core\authentication\Authenticator;
use Obcato\Core\database\dao\ArticleDao;
use Obcato\Core\database\dao\ArticleDaoMysql;
use Obcato\Core\modules\articles\model\Article;
use Obcato\Core\modules\articles\model\ArticleMetadataField;
use Obcato\Core\modules\articles\model\ArticleTerm;
use Obcato\Core\modules\pages\model\Page;
use const Obcato\Core\ELEMENT_HOLDER_ARTICLE;

class ArticleInteractor implements ArticleService {

    private static ?ArticleInteractor $instance = null;
    private ArticleDao $articleDao;

    private function __construct() {
        $this->articleDao = ArticleDaoMysql::getInstance();
    }

    public static function getInstance(): ArticleInteractor {
        if (!self::$instance) {
            self::$instance = new ArticleInteractor();
        }
        return self::$instance;
    }

    public function updateArticle(Article $article): void {
        $this->articleDao->updateArticle($article);
    }

    public function getAllChildArticlesFor(Article $article): array {
        return $this->articleDao->getAllChildArticles($article->getId());
    }

    public function createArticle(): Article {
        $newArticle = new Article();
        $newArticle->setPublished(false);
        $newArticle->setTitle('Nieuw artikel');
        $newArticle->setName('Nieuw artikel');
        $newArticle->setCreatedById(Authenticator::getCurrentUser()->getId());
        $newArticle->setType(ELEMENT_HOLDER_ARTICLE);
        $this->articleDao->createArticle($newArticle);
        return $newArticle;
    }

    public function deleteArticle(Article $article): void {
        $this->articleDao->deleteArticle($article);
    }

    public function getTermsForArticle(Article $article): array {
        return $this->articleDao->getTermsForArticle($article->getId());
    }

    public function addTermToArticle(int $termId, Article $article): void {
        $this->articleDao->addTermToArticle($termId, $article);
    }

    public function deleteTermFromArticle(int $termId, Article $article): void {
        $this->articleDao->deleteTermFromArticle($termId, $article);
    }

    public function getArticle(int $id): Article {
        return $this->articleDao->getArticle($id);
    }

    public function getArticleComments(int $articleId): array {
        return $this->articleDao->getArticleComments($articleId);
    }

    public function getChildArticleComments(int $commentId): array {
        return $this->articleDao->getChildArticleComments($commentId);
    }

    public function deleteTargetPage(Page $page): void {
        $this->articleDao->deleteTargetPage($page->getId());
    }

    public function setDefaultArticleTargetPage(int $pageId): void {
        $this->articleDao->setDefaultArticleTargetPage($pageId);
    }

    public function addTargetPage(int $pageId): void {
        $this->articleDao->addTargetPage($pageId);
    }

    public function searchArticles(?string $searchQuery, ?int $termId): array {
        return $this->articleDao->searchArticles($searchQuery, $termId);
    }

    public function getAllArticles(): array {
        return $this->articleDao->getAllArticles();
    }

    public function getTerm(string $name): ArticleTerm {
        return $this->articleDao->getTerm($name);
    }

    public function getMetadataFields(): array {
        return $this->articleDao->getMetadataFields();
    }

    public function getMetadataField(int $id): ?ArticleMetadataField {
        return $this->articleDao->getMetadataField($id);
    }

    public function createNewArticleMetadataField(string $name): ArticleMetadataField {
        return $this->articleDao->createNewArticleMetadataField($name);
    }

    public function updateMetadataField(ArticleMetadataField $field): void {
        $this->articleDao->updateMetadataField($field);
    }

    public function deleteMetadataField(ArticleMetadataField $field): void {
        $this->articleDao->deleteMetadataField($field);
    }
}