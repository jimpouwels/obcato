<?php

namespace Obcato\Core\modules\articles;

use Obcato\Core\core\form\Form;
use Obcato\Core\core\form\FormException;
use Obcato\Core\database\dao\ArticleDao;
use Obcato\Core\database\dao\ArticleDaoMysql;
use Obcato\Core\modules\articles\model\Article;
use Obcato\Core\utilities\DateUtility;

class ArticleForm extends Form {

    private Article $article;
    private array $selectedTerm;
    private ArticleDao $articleDao;

    public function __construct(Article $article) {
        $this->article = $article;
        $this->articleDao = ArticleDaoMysql::getInstance();
    }

    public function loadFields(): void {
        $this->article->setTitle($this->getMandatoryFieldValue("title"));
        $this->article->setUrlTitle($this->getFieldValue('url_title'));
        $this->article->setTemplateId($this->getNumber('template'));
        $this->article->setKeywords($this->getFieldValue('keywords'));
        $this->article->setDescription($this->getFieldValue("article_description"));
        $this->article->setPublished($this->getCheckboxValue("article_published"));
        $this->article->setImageId($this->getNumber("article_image_ref_" . $this->article->getId()));
        $this->article->setWallpaperId($this->getNumber("article_wallpaper_ref_" . $this->article->getId()));
        $this->article->setTargetPageId($this->getNumber("article_target_page"));
        $this->article->setParentArticleId($this->getNumber("parent_article_id"));
        $this->article->setCommentWebFormId($this->getNumber("article_comment_webform"));
        $publicationDate = $this->loadPublicationDate();
        $sortDate = $this->loadSortDate();
        $this->deleteLeadImageIfNeeded();
        $this->deleteWallpaperIfNeeded();
        $this->deleteParentArticleIfNeeded();
        $this->selectedTerm = $this->getSelectValue("select_terms_" . $this->article->getId());
        if ($this->hasErrors()) {
            throw new FormException();
        } else {
            $this->article->setPublicationDate(DateUtility::stringMySqlDate($publicationDate));
            $this->article->setSortDate(DateUtility::stringMySqlDate($sortDate));
        }
    }

    public function getSelectedTerm(): array {
        return $this->selectedTerm;
    }

    public function getTermsToDelete(): array {
        $termsToDelete = array();
        $articleTerms = $this->articleDao->getTermsForArticle($this->article->getId());
        foreach ($articleTerms as $articleTerm) {
            if ($this->getFieldValue("term_" . $this->article->getId() . "_" . $articleTerm->getId() . "_delete")) {
                $termsToDelete[] = $articleTerm;
            }
        }
        return $termsToDelete;
    }

    private function deleteLeadImageIfNeeded(): void {
        if ($this->getFieldValue("delete_lead_image_field") == "true") {
            $this->article->setImageId(null);
        }
    }

    private function deleteWallpaperIfNeeded(): void {
        if ($this->getFieldValue("delete_wallpaper_field") == "true") {
            $this->article->setWallpaperId(null);
        }
    }

    private function deleteParentArticleIfNeeded(): void {
        if ($this->getFieldValue("delete_parent_article_field") == "true") {
            $this->article->setParentArticleId(null);
        }
    }

    private function loadPublicationDate(): string {
        return $this->getMandatoryDate("publication_date");
    }

    private function loadSortDate(): string {
        return $this->getMandatoryDate("sort_date");
    }

}