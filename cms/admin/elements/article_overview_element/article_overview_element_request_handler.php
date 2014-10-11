<?php
    
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/request_handlers/http_request_handler.php";
    require_once CMS_ROOT . "elements/article_overview_element/article_overview_element_form.php";
    require_once CMS_ROOT . "database/dao/element_dao.php";
    require_once CMS_ROOT . "database/dao/article_dao.php";

    class ArticleOverviewElementRequestHandler extends HttpRequestHandler {

        private $_article_overview_element;
        private $_element_dao;
        private $_article_dao;
        private $_article_overview_element_form;

        public function __construct($article_overview_element) {
            $this->_article_overview_element = $article_overview_element;
            $this->_element_dao = ElementDao::getInstance();
            $this->_article_dao = ArticleDao::getInstance();
            $this->_article_overview_element_form = new ArticleOverviewElementForm($article_overview_element);
        }

        public function handleGet() {
        }

        public function handlePost()
        {
            try {
                $this->_article_overview_element_form->loadFields();
                $this->removeSelectedTerms();
                $this->addSelectedTerms();
                $this->_element_dao->updateElement($this->_article_overview_element);
            } catch (FormException $e) {
                Notifications::setFailedMessage("Er is een artikeloverzicht niet opgeslagen, verwerk de fouten");
            }
        }

        private function addSelectedTerms() {
            $selected_terms = $this->_article_overview_element_form->getSelectedTerms();
            if ($selected_terms) {
                foreach ($selected_terms as $selected_term_id) {
                    $term = $this->_article_dao->getTerm($selected_term_id);
                    $this->_article_overview_element->addTerm($term);
                }
            }
        }

        private function removeSelectedTerms() {
            foreach ($this->_article_overview_element_form->getTermsToRemove() as $term)
                $this->_article_overview_element->removeTerm($term);
        }
    }
?>