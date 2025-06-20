<?php

namespace Obcato\Core\frontend;

use Obcato\Core\database\dao\ImageDao;
use Obcato\Core\database\dao\ImageDaoMysql;
use Obcato\Core\elements\article_overview_element\ArticleOverviewElement;
use Obcato\Core\modules\articles\model\Article;
use Obcato\Core\modules\articles\service\ArticleInteractor;
use Obcato\Core\modules\articles\service\ArticleService;
use Obcato\Core\modules\blocks\model\Block;
use Obcato\Core\modules\images\model\Image;
use Obcato\Core\modules\pages\model\Page;
use Obcato\Core\utilities\DateUtility;

class ArticleOverviewElementFrontendVisual extends ElementFrontendVisual {

    private ImageDao $imageDao;
    private ArticleService $articleService;

    public function __construct(Page $page, ?Article $article, ?Block $block, ArticleOverviewElement $articleOverviewElement) {
        parent::__construct($page, $article, $block, $articleOverviewElement);
        $this->imageDao = ImageDaoMysql::getInstance();
        $this->articleService = ArticleInteractor::getInstance();
    }

    public function loadElement(array &$data): void {
        $data["title"] = $this->toHtml($this->getElement()->getTitle(), $this->getElementHolder());
        $data["articles"] = $this->getArticles();
        $data["terms"] = $this->getTermsData();
    }

    private function getTermsData(): array {
        $termsData = array();
        foreach ($this->getElement()->getTerms() as $term) {
            $termsData[] = $term->getName();
        }
        return $termsData;
    }

    private function getArticles(): array {
        $articles = $this->getElement()->getArticles($this->getArticle()?->getId(), $this->getArticle()?->getParentArticleId());
        $articlesData = array();
        foreach ($articles as $article) {
            if (!$this->isPublished($article) || $this->getArticle()?->getId() == $article->getId()) continue;
            $articleData = array();
            $articleData["id"] = $article->getId();
            $articleData["title"] = $article->getTitle();
            $articleData["url"] = $this->getLinkHelper()->createArticleUrl($article);
            $articleData["description"] = $this->toHtml($article->getDescription(), $article);
            $articleData["publication_date"] = DateUtility::mysqlDateToString($article->getPublicationDate(), '-');
            $articleData["sort_date_in_past"] = strtotime($article->getSortDate()) < strtotime(date('Y-m-d H:i:s', strtotime('00:00:00')));
            $articleData["sort_date"] = DateUtility::mysqlDateToString($article->getSortDate(), '-');
            $articleData["image"] = $this->getImageData($this->imageDao->getImage($article->getImageId()));
            $articleData["wallpaper"] = $this->getImageData($this->imageDao->getImage($article->getWallpaperId()));

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

    private function getImageData(?Image $image): ?array {
        $imageData = array();
        if ($image) {
            $imageData["title"] = $image->getTitle();
            $imageData["alt_text"] = $image->getAltText();
            $imageData["location"] = $image->getLocation();
            $imageData["url"] = $this->getLinkHelper()->createImageUrl($image);
        }
        return $imageData;
    }

    private function isPublished(Article $article): bool {
        return $article->isPublished() && strtotime($article->getPublicationDate()) < strtotime('now');
    }
}