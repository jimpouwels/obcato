<?php

    
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "request_handlers/http_request_handler.php";
    require_once CMS_ROOT . "database/dao/article_dao.php";
    require_once CMS_ROOT . "modules/articles/term_form.php";
    
    class TermPreHandler extends HttpRequestHandler {

        private $_current_term;
        private $_article_dao;
        
        public function __construct() {
            $this->_article_dao = ArticleDao::getInstance();
        }
    
        public function handleGet() {
            $this->_current_term = $this->getTermFromGetRequest();
        }
        
        public function handlePost() {
            $this->_current_term = $this->getTermFromPostRequest();
            if ($this->isAddTermAction())
                $this->addTerm();
            else if ($this->isUpdateTermAction())
                $this->updateTerm();
            else if ($this->isDeleteTermsAction())
                $this->deleteTerms();
        }
        
        public function getCurrentTerm() {
            return $this->_current_term;
        }
        
        private function addTerm() {
            $new_term = $this->_article_dao->createTerm($this->getTextResource("articles_terms_default_term_name"));
            $this->sendSuccessMessage($this->getTextResource("articles_terms_term_create_success_message"));
            $this->redirectTo($this->getBackendBaseUrl() . "&term=" . $new_term->getId());
        }
        
        private function updateTerm() {
            $term_form = new TermForm($this->_current_term);
            try {
                $term_form->loadFields();
                $this->_article_dao->updateTerm($this->_current_term);
                $this->sendSuccessMessage($this->getTextResource("articles_terms_term_save_success_message"));
            } catch (FormException $e) {
                $this->sendErrorMessage($this->getTextResource("articles_terms_term_save_error_message"));
            }
        }
        
        private function deleteTerms() {
            $terms = $this->_article_dao->getAllTerms();
            foreach ($terms as $term) {
                if (isset($_POST["term_" . $term->getId() . "_delete"]))
                    $this->_article_dao->deleteTerm($term);
            }
            $this->sendSuccessMessage($this->getTextResource("articles_terms_term_delete_success_message"));
        }
        
        private function getTermFromGetRequest() {
            if (isset($_GET["term"])) {
                return $this->getTerm($_GET["term"]);
            }
        }
        
        private function getTermFromPostRequest() {
            if (isset($_POST["term_id"]) && $_POST["term_id"] != "") {
                return $this->getTerm($_POST["term_id"]);
            }
        }
        
        private function getTerm($term_id) {
            return $this->_article_dao->getTerm($term_id);
        }
        
        private function isUpdateTermAction() {
            return isset($_POST["action"]) && $_POST["action"] == "update_term";
        }
        
        private function isAddTermAction() {
            return isset($_POST["add_term_action"]) && $_POST["add_term_action"] == "add_term";
        }
        
        private function isDeleteTermsAction() {
            return isset($_POST["term_delete_action"]) && $_POST["term_delete_action"] == "delete_terms";
        }
    }
    
?>