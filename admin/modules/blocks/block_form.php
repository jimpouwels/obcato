<?php
defined("_ACCESS") or die;

require_once CMS_ROOT . "/core/form/form.php";

class BlockForm extends Form {

    private Block $_block;

    public function __construct(Block $block) {
        $this->_block = $block;
    }

    public function loadFields(): void {
        $this->_block->setTitle($this->getMandatoryFieldValue("title", "Titel is verplicht"));
        $this->_block->setPublished($this->getCheckboxValue("published"));
        $this->_block->setPositionId($this->getFieldValue("block_position"));
        $this->_block->setTemplateId($this->getNumber("block_template", "not a number"));
        if ($this->hasErrors()) {
            throw new FormException();
        }
    }

}
    