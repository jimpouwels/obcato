<?php

namespace Obcato\Core\admin\modules\articles\service;

use Obcato\Core\admin\authentication\Authenticator;
use Obcato\Core\admin\database\dao\ArticleDao;
use Obcato\Core\admin\database\dao\ArticleDaoMysql;
use Obcato\Core\admin\modules\articles\model\Article;
use Obcato\Core\admin\modules\pages\model\Page;
use const Obcato\Core\admin\ELEMENT_HOLDER_ARTICLE;

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

    public function deleteTargetPage(Page $page): void {
        $this->articleDao->deleteTargetPage($page->getId());
    }

    public function setDefaultArticleTargetPage(int $pageId): void {
        $this->articleDao->setDefaultArticleTargetPage($pageId);
    }

    public function addTargetPage(int $pageId): void {
        $this->articleDao->addTargetPage($pageId);
    }
}