<?php

    defined("_ACCESS") or die;
    
    require_once CMS_ROOT . "core/form/form.php";
    
    class PageForm extends Form {
    
        private $_page;
        private $_element_order;
        private $_selected_blocks;
    
        public function __construct($page) {
            $this->_page = $page;
        }
    
        public function loadFields() {
            $this->_page->setTitle($this->getMandatoryFieldValue("page_title", "Titel is verplicht"));
            $this->_page->setPublished($this->getCheckboxValue("published"));
            $this->_page->setNavigationTitle($this->getMandatoryFieldValue("navigation_title", "Navigatietitel is verplicht"));
            $this->_page->setDescription($this->getFieldValue("description"));
            $this->_page->setShowInNavigation($this->getCheckboxValue("show_in_navigation"));
            $this->_page->setTemplateId($this->getFieldValue("page_template"));
            $this->_element_order = $this->getFieldValue("element_order");
            $this->_selected_blocks = $this->getFieldValue("select_blocks_" . $this->_page->getId());
            if ($this->hasErrors()) {
                throw new FormException();
            }
        }
        
        public function getElementOrder() {
            return $this->_element_order;
        }
        
        public function getSelectedBlocks() {
            return $this->_selected_blocks;
        }
    
    }
    