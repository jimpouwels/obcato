<?php

namespace Obcato\Core\frontend;

use Obcato\Core\database\dao\ImageDao;
use Obcato\Core\database\dao\ImageDaoMysql;
use Obcato\Core\elements\article_overview_element\ArticleOverviewElement;
use Obcato\Core\modules\articles\model\Article;
use Obcato\Core\modules\articles\service\ArticleInteractor;
use Obcato\Core\modules\articles\service\ArticleService;
use Obcato\Core\modules\pages\model\Page;
use Obcato\Core\utilities\DateUtility;

class ArticleOverviewElementFrontendVisual extends ElementFrontendVisual {

    private ImageDao $imageDao;
    private ArticleService $articleService;

    public function __construct(Page $page, ?Article $article, ArticleOverviewElement $articleOverviewElement) {
        parent::__construct($page, $article, $articleOverviewElement);
        $this->imageDao = ImageDaoMysql::getInstance();
        $this->articleService = ArticleInteractor::getInstance();
    }

    public function loadElement(): void {
        $this->assign("title", $this->toHtml($this->getElement()->getTitle(), $this->getElementHolder()));
        $this->assign("articles", $this->getArticles());
    }

    private function getArticles(): array {
        $articles = $this->getElement()->getArticles();
        $articlesData = array();
        foreach ($articles as $article) {
            if (!$this->isPublished($article) || $this->getArticle()?->getId() == $article->getId()) continue;
            $articleData = array();
            $articleData["id"] = $article->getId();
            $articleData["title"] = $article->getTitle();
            $articleData["url"] = $this->getArticleUrl($article);
            $articleData["description"] = $this->toHtml($article->getDescription(), $article);
            $articleData["publication_date"] = DateUtility::mysqlDateToString($article->getPublicationDate(), '-');
            $articleData["sort_date_in_past"] = strtotime($article->getSortDate()) < strtotime(date('Y-m-d H:i:s', strtotime('00:00:00')));
            $articleData["sort_date"] = DateUtility::mysqlDateToString($article->getSortDate(), '-');
            $articleData["image"] = $this->getArticleImage($article);

            $terms = $this->articleService->getTermsForArticle($article);
            $termsData = array();
            foreach ($terms as $term) {
                $termsData[] = $term->getName();
            }
            $articleData["terms"] = $termsData;

            $articlesData[] = $articleData;
        }
        return $articlesData;
    }

    private function getArticleImage(Article $article): ?array {
        $image = $this->imageDao->getImage($article->getImageId());
        $imageData = array();
        if ($image) {
            $imageData["title"] = $image->getTitle();
            $imageData["alt_text"] = $image->getAltText();
            $imageData["url"] = $this->getImageUrl($image);
        }
        return $imageData;
    }

    private function isPublished(Article $article): bool {
        return $article->isPublished() && strtotime($article->getPublicationDate()) < strtotime('now');
    }
}