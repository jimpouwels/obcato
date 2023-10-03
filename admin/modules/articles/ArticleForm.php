<?php

defined("_ACCESS") or die;

require_once CMS_ROOT . "/core/form/Form.php";
require_once CMS_ROOT . "/database/dao/ArticleDaoMysql.php";
require_once CMS_ROOT . "/utilities/DateUtility.php";

class ArticleForm extends Form {

    private Article $_article;
    private string $_element_order;
    private array $_selected_terms;
    private ArticleDao $_article_dao;

    public function __construct(Article $article) {
        $this->_article = $article;
        $this->_article_dao = ArticleDaoMysql::getInstance();
    }

    public function loadFields(): void {
        $this->_article->setTitle($this->getMandatoryFieldValue("article_title", "Titel is verplicht"));
        $this->_article->setTemplateId($this->getNumber('template', "No number"));
        $this->_article->setKeywords($this->getFieldValue('keywords'));
        $this->_article->setDescription($this->getFieldValue("article_description"));
        $this->_article->setPublished($this->getCheckboxValue("article_published"));
        $this->_article->setImageId($this->getNumber("article_image_ref_" . $this->_article->getId(), $this->getTextResource("form_invalid_number_error")));
        $this->_article->setTargetPageId($this->getNumber("article_target_page", $this->getTextResource("form_invalid_number_error")));
        $this->_article->setParentArticleId($this->getNumber("parent_article_id", $this->getTextResource("form_invalid_number_error")));
        $this->_article->setCommentWebFormId($this->getNumber("article_comment_webform", $this->getTextResource("form_invalid_number_error")));
        $publication_date = $this->loadPublicationDate();
        $sort_date = $this->loadSortDate();
        $this->deleteLeadImageIfNeeded();
        $this->deleteParentArticleIfNeeded();
        $this->_selected_terms = $this->getSelectValue("select_terms_" . $this->_article->getId());
        if ($this->hasErrors()) {
            throw new FormException();
        } else {
            $this->_article->setPublicationDate(DateUtility::stringMySqlDate($publication_date));
            $this->_article->setSortDate(DateUtility::stringMySqlDate($sort_date));
        }
    }

    public function getSelectedTerms(): array {
        return $this->_selected_terms;
    }

    public function getTermsToDelete(): array {
        $terms_to_delete = array();
        $article_terms = $this->_article_dao->getTermsForArticle($this->_article->getId());
        foreach ($article_terms as $article_term) {
            if (!is_null($this->getFieldValue("term_" . $this->_article->getId() . "_" . $article_term->getId() . "_delete"))) {
                $terms_to_delete[] = $article_term;
            }
        }
        return $terms_to_delete;
    }

    private function deleteLeadImageIfNeeded(): void {
        if ($this->getFieldValue("delete_lead_image_field") == "true") {
            $this->_article->setImageId(null);
        }
    }

    private function deleteParentArticleIfNeeded(): void {
        if ($this->getFieldValue("delete_parent_article_field") == "true") {
            $this->_article->setParentArticleId(null);
        }
    }

    private function loadPublicationDate(): string {
        return $this->getMandatoryDate("publication_date", "Datum is verplicht", "Vul een datum in (bijv. 31-12-2010)");
    }

    private function loadSortDate(): string {
        return $this->getMandatoryDate("sort_date", "Datum is verplicht", "Vul een geldige datum in (bijv. 31-12-2010)");
    }

}
    