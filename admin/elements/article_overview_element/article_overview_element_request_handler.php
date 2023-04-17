<?php
    
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "request_handlers/http_request_handler.php";
    require_once CMS_ROOT . "elements/article_overview_element/article_overview_element_form.php";
    require_once CMS_ROOT . "database/dao/element_dao.php";
    require_once CMS_ROOT . "database/dao/article_dao.php";
    require_once CMS_ROOT . "elements/element_contains_errors_exception.php";

    class ArticleOverviewElementRequestHandler extends HttpRequestHandler {

        private ArticleOverviewElement $_article_overview_element;
        private ElementDao $_element_dao;
        private ArticleDao $_article_dao;
        private ArticleOverviewElementForm $_article_overview_element_form;

        public function __construct(ArticleOverviewElement $article_overview_element) {
            $this->_article_overview_element = $article_overview_element;
            $this->_element_dao = ElementDao::getInstance();
            $this->_article_dao = ArticleDao::getInstance();
            $this->_article_overview_element_form = new ArticleOverviewElementForm($article_overview_element);
        }

        public function handleGet(): void {
        }

        public function handlePost(): void {
            try {
                $this->_article_overview_element_form->loadFields();
                $this->removeSelectedTerms();
                $this->addSelectedTerms();
                $this->_element_dao->updateElement($this->_article_overview_element);
            } catch (FormException $e) {
                throw new ElementContainsErrorsException("Article overview element contains errors");
            }
        }

        private function addSelectedTerms(): void {
            $selected_terms = $this->_article_overview_element_form->getSelectedTerms();
            if ($selected_terms) {
                foreach ($selected_terms as $selected_term_id) {
                    $term = $this->_article_dao->getTerm($selected_term_id);
                    $this->_article_overview_element->addTerm($term);
                }
            }
        }

        private function removeSelectedTerms(): void {
            foreach ($this->_article_overview_element_form->getTermsToRemove() as $term) {
                $this->_article_overview_element->removeTerm($term);
            }
        }
    }
?>