<?php
    
    defined("_ACCESS") or die;

    require_once CMS_ROOT . "view/forms/form.php";
    require_once CMS_ROOT . "libraries/utilities/date_utility.php";

    class ArticleOverviewElementForm extends Form {

        private $_article_overview_element;
        private $_selected_terms;
        private $_removed_terms;

        public function __construct($article_overview_element) {
            $this->_article_overview_element = $article_overview_element;
        }

        public function loadFields() {
            $element_id = $this->_article_overview_element->getId();
            $title = $this->getFieldValue('element_' . $element_id . '_title');
            $show_to = $this->getDate('element_' . $element_id . '_show_to', 'Vul een datum in (bijv. 31-12-2010)');
            $show_from = $this->getDate('element_' . $element_id . '_show_from', 'Vul een datum in (bijv. 31-12-2010)');
            $number_of_results = $this->getNumber('element_' . $element_id . '_number_of_results', 'Vul een geldig getal in');
            $order_by = $this->getFieldValue('element_' . $element_id . '_order_by');
            $order_type = $this->getFieldValue('element_' . $element_id . '_order_type');
            $template_id = $this->getFieldValue('element_' . $element_id . '_template');
            if ($this->hasErrors())
                throw new FormException();
            else {
                $this->_article_overview_element->setTitle($title);
                $this->_article_overview_element->setShowTo(DateUtility::stringMySqlDate($show_to));
                $this->_article_overview_element->setShowFrom(DateUtility::stringMySqlDate($show_from));
                $this->_article_overview_element->setNumberOfResults($number_of_results);
                $this->_article_overview_element->setOrderBy($order_by);
                $this->_article_overview_element->setOrderType($order_type);
                $this->_article_overview_element->setTemplateId($template_id);
            }

            $this->_selected_terms = $this->getFieldValue('select_terms_' . $this->_article_overview_element->getId());
            $this->_removed_terms = $this->getTermsToDeleteFromPostRequest();
        }

        public function getSelectedTerms() {
            return $this->_selected_terms;
        }

        public function getTermsToRemove() {
            return $this->_removed_terms;
        }

        private function getTermsToDeleteFromPostRequest() {
            $terms_to_remove = array();
            $element_terms = $this->_article_overview_element->getTerms();
            foreach ($element_terms as $element_term) {
                if (isset($_POST['term_' . $this->_article_overview_element->getId() . '_' . $element_term->getId() . '_delete']))
                    $terms_to_remove[] = $element_term;
            }
            return $terms_to_remove;
        }

    }