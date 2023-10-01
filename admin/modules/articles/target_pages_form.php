<?php

    defined("_ACCESS") or die;
    
    require_once CMS_ROOT . "core/form/form.php";
    require_once CMS_ROOT . "database/dao/article_dao.php";
    
    class TargetPagesForm extends Form {
    
        private string $_target_page_to_add;
        private string $_new_default_target_page;
        private array $_target_pages_to_delete;
        private ArticleDao $_article_dao;
    
        public function __construct() {
            $this->_article_dao = ArticleDao::getInstance();
        }
    
        public function loadFields(): void {
            $this->_target_page_to_add = $this->getFieldValue("add_target_page_ref");
            $this->_new_default_target_page = $this->getFieldValue("new_default_target_page");
            $this->loadTargetPagesToDelete();
        }
        
        public function getTargetPageToAdd(): string {
            return $this->_target_page_to_add;
        }
        
        public function getNewDefaultTargetPage(): string {
            return $this->_new_default_target_page;
        }
        
        public function getTargetPagesToDelete(): array {
            return $this->_target_pages_to_delete;
        }
        
        private function loadTargetPagesToDelete(): void {
            $target_pages = $this->_article_dao->getTargetPages();
            foreach($target_pages as $target_page) {
                $field_to_check = "target_page_" . $target_page->getId() . "_delete";
                if (isset($_POST[$field_to_check]) && $_POST[$field_to_check] != "") {
                    $this->_target_pages_to_delete[] = $target_page->getId();
                }
            }
        }
    
    }
    