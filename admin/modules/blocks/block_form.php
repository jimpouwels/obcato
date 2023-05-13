<?php
    defined("_ACCESS") or die;
    
    require_once CMS_ROOT . "core/form/form.php";
    
    class BlockForm extends Form {
    
        private Block $_block;
        private string $_element_order;
    
        public function __construct(Block $block) {
            $this->_block = $block;
        }
    
        public function loadFields(): void {
            $this->_block->setTitle($this->getMandatoryFieldValue("title", "Titel is verplicht"));
            $this->_block->setPublished($this->getCheckboxValue("published"));
            $this->_block->setPositionId($this->getFieldValue("block_position"));
            $this->_block->setTemplateId($this->getFieldValue("block_template"));
            $this->_element_order = $this->getFieldValue("draggable_order");
            if ($this->hasErrors()) {
                throw new FormException();
            }
        }
        
        public function getElementOrder(): string {
            return $this->_element_order;
        }
        
    }
    