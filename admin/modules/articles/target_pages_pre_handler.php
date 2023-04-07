<?php

    
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "request_handlers/http_request_handler.php";
    require_once CMS_ROOT . "database/dao/article_dao.php";
    require_once CMS_ROOT . "modules/articles/target_pages_form.php";
    
    class TargetPagesPreHandler extends HttpRequestHandler {
        
        private $_target_pages_form;
        private $_article_dao;
        
        public function __construct() {
            $this->_target_pages_form = new TargetPagesForm();
            $this->_article_dao = ArticleDao::getInstance();
        }
    
        public function handleGet() {
        }
        
        public function handlePost() {
            $this->_target_pages_form->loadFields();
            if ($this->isUpdateOptionsAction())
                $this->updateOptions();
            if ($this->isChangeDefaultTargetPageAction())
                $this->changeDefaultTargetPage();
            if ($this->isDeleteTargetPagesAction())
                $this->deleteTargetPages();
        }
        
        private function deleteTargetPages() {
            foreach ($this->_target_pages_form->getTargetPagesToDelete() as $target_page_to_delete) {
                $this->_article_dao->deleteTargetPage($target_page_to_delete);
            }
        }
        
        private function changeDefaultTargetPage() {
            $new_default_target_page = $this->_target_pages_form->getNewDefaultTargetPage();
            if ($new_default_target_page != "") {
                $this->_article_dao->setDefaultArticleTargetPage($new_default_target_page);
            }
        }
    
        private function updateOptions() {
            $target_page_to_add = $this->_target_pages_form->getTargetPageToAdd();
            if ($target_page_to_add != "")
                $this->_article_dao->addTargetPage($target_page_to_add);
        }
        
        private function isUpdateOptionsAction() {
            return isset($_POST["action"]) && $_POST["action"] == "target_page_to_add";
        }
        
        private function isChangeDefaultTargetPageAction() {
            return isset($_POST["action"]) && $_POST["action"] == "change_default_target_page";
        }
        
        private function isDeleteTargetPagesAction() {
            return isset($_POST["action"]) && $_POST["action"] == "delete_target_pages";
        }
    }
?>