<?php

require_once CMS_ROOT . "/request_handlers/ElementForm.php";
require_once CMS_ROOT . "/utilities/DateUtility.php";

class ArticleOverviewElementForm extends ElementForm {

    private ArticleOverviewElement $_article_overview_element;
    private array $_selected_terms;
    private array $_removed_terms;

    public function __construct(ArticleOverviewElement $article_overview_element) {
        parent::__construct($article_overview_element);
        $this->_article_overview_element = $article_overview_element;
    }

    public function loadFields(): void {
        $element_id = $this->_article_overview_element->getId();
        $title = $this->getFieldValue('element_' . $element_id . '_title');
        $show_to = $this->getDate('element_' . $element_id . '_show_to');
        $show_from = $this->getDate('element_' . $element_id . '_show_from');
        $number_of_results = $this->getNumber('element_' . $element_id . '_number_of_results');
        $order_by = $this->getFieldValue('element_' . $element_id . '_order_by');
        $order_type = $this->getFieldValue('element_' . $element_id . '_order_type');
        if ($this->hasErrors())
            throw new FormException();
        else {
            parent::loadFields();
            $this->_article_overview_element->setTitle($title);
            $this->_article_overview_element->setShowTo(DateUtility::stringMySqlDate($show_to));
            $this->_article_overview_element->setShowFrom(DateUtility::stringMySqlDate($show_from));
            $this->_article_overview_element->setNumberOfResults($number_of_results);
            $this->_article_overview_element->setOrderBy($order_by);
            $this->_article_overview_element->setOrderType($order_type);
        }

        $this->_selected_terms = $this->getFieldValues('select_terms_' . $this->_article_overview_element->getId());
        $this->_removed_terms = $this->getTermsToDeleteFromPostRequest();
    }

    public function getSelectedTerms(): array {
        return $this->_selected_terms;
    }

    public function getTermsToRemove(): array {
        return $this->_removed_terms;
    }

    private function getTermsToDeleteFromPostRequest(): array {
        $terms_to_remove = array();
        $element_terms = $this->_article_overview_element->getTerms();
        foreach ($element_terms as $element_term) {
            if (isset($_POST['term_' . $this->_article_overview_element->getId() . '_' . $element_term->getId() . '_delete'])) {
                $terms_to_remove[] = $element_term;
            }
        }
        return $terms_to_remove;
    }

}