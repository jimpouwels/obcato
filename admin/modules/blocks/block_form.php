<?php
    defined("_ACCESS") or die;
    
    require_once CMS_ROOT . "core/form/element_holder_form.php";
    
    class BlockForm extends ElementHolderForm {
    
        private Block $_block;
        private string $_element_order;
    
        public function __construct(Block $block) {
            parent::__construct($block);
            $this->_block = $block;
        }
    
        public function loadFields(): void {
            parent::loadFields();
            $this->_block->setTitle($this->getMandatoryFieldValue("title", "Titel is verplicht"));
            $this->_block->setPublished($this->getCheckboxValue("published"));
            $this->_block->setPositionId($this->getFieldValue("block_position"));
            $this->_block->setTemplateId($this->getFieldValue("block_template"));
            if ($this->hasErrors()) {
                throw new FormException();
            }
        }
        
    }
    