<?php

    defined("_ACCESS") or die;
    
    require_once CMS_ROOT . "core/form/form.php";
    
    class PageForm extends Form {
    
        private Page $_page;
        private string $_element_order;
        private array $_selected_blocks;
    
        public function __construct(Page $page) {
            $this->_page = $page;
        }
    
        public function loadFields(): void {
            $this->_page->setTitle($this->getMandatoryFieldValue("page_title", "Titel is verplicht"));
            $this->_page->setPublished($this->getCheckboxValue("published"));
            $this->_page->setNavigationTitle($this->getMandatoryFieldValue("navigation_title", "Navigatietitel is verplicht"));
            $this->_page->setDescription($this->getFieldValue("description"));
            $this->_page->setShowInNavigation($this->getCheckboxValue("show_in_navigation"));
            $this->_page->setTemplateId($this->getNumber("page_template", "Vul een geldig getal in"));
            $this->_element_order = $this->getFieldValue("element_order");
            $this->_selected_blocks = $this->getFieldValues("select_blocks_" . $this->_page->getId());
            if ($this->hasErrors()) {
                throw new FormException();
            }
        }
        
        public function getElementOrder(): string {
            return $this->_element_order;
        }
        
        public function getSelectedBlocks(): array {
            return $this->_selected_blocks;
        }
    
    }
    