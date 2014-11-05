<?php

    defined("_ACCESS") or die;
    
    require_once CMS_ROOT . "request_handlers/form.php";
    
    class BlockForm extends Form {
    
        private $_block;
        private $_element_order;
    
        public function __construct($block) {
            $this->_block = $block;
        }
    
        public function loadFields() {
            $this->_block->setTitle($this->getMandatoryFieldValue("title", "Titel is verplicht"));
            $this->_block->setPublished($this->getCheckboxValue("published"));
            $this->_block->setPositionId($this->getFieldValue("block_position"));
            $this->_block->setTemplateId($this->getFieldValue("block_template"));
            $this->_element_order = $this->getFieldValue("element_order");
            if ($this->hasErrors())
                throw new FormException();
        }
        
        public function getElementOrder() {
            return $this->_element_order;
        }
        
    }
    