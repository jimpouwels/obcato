<?php

require_once CMS_ROOT . "/request_handlers/ElementForm.php";
require_once CMS_ROOT . "/utilities/DateUtility.php";

class ArticleOverviewElementForm extends ElementForm {

    private ArticleOverviewElement $articleOverviewElement;
    private array $selectedTerms;
    private array $removedTerms;

    public function __construct(ArticleOverviewElement $articleOverviewElement) {
        parent::__construct($articleOverviewElement);
        $this->articleOverviewElement = $articleOverviewElement;
    }

    public function loadFields(): void {
        $elementId = $this->articleOverviewElement->getId();
        $title = $this->getFieldValue('element_' . $elementId . '_title');
        $showTo = $this->getDate('element_' . $elementId . '_show_to');
        $showFrom = $this->getDate('element_' . $elementId . '_show_from');
        $numberOfResults = $this->getNumber('element_' . $elementId . '_number_of_results');
        $orderBy = $this->getFieldValue('element_' . $elementId . '_order_by');
        $orderType = $this->getFieldValue('element_' . $elementId . '_order_type');
        if ($this->hasErrors()) {
            throw new FormException();
        } else {
            parent::loadFields();
            $this->articleOverviewElement->setTitle($title);
            $this->articleOverviewElement->setShowTo(DateUtility::stringMySqlDate($showTo));
            $this->articleOverviewElement->setShowFrom(DateUtility::stringMySqlDate($showFrom));
            $this->articleOverviewElement->setNumberOfResults($numberOfResults);
            $this->articleOverviewElement->setOrderBy($orderBy);
            $this->articleOverviewElement->setOrderType($orderType);
        }

        $this->selectedTerms = $this->getFieldValues('select_terms_' . $this->articleOverviewElement->getId());
        $this->removedTerms = $this->getTermsToDeleteFromPostRequest();
    }

    public function getSelectedTerms(): array {
        return $this->selectedTerms;
    }

    public function getTermsToRemove(): array {
        return $this->removedTerms;
    }

    private function getTermsToDeleteFromPostRequest(): array {
        $termsToRemove = array();
        $elementTerms = $this->articleOverviewElement->getTerms();
        foreach ($elementTerms as $element_term) {
            if (isset($_POST['term_' . $this->articleOverviewElement->getId() . '_' . $element_term->getId() . '_delete'])) {
                $termsToRemove[] = $element_term;
            }
        }
        return $termsToRemove;
    }

}