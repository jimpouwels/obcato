<?php
require_once CMS_ROOT . "/core/form/Form.php";

class BlockForm extends Form {

    private Block $block;

    public function __construct(Block $block) {
        $this->block = $block;
    }

    public function loadFields(): void {
        $this->block->setTitle($this->getMandatoryFieldValue("title"));
        $this->block->setPublished($this->getCheckboxValue("published"));
        $this->block->setPositionId($this->getFieldValue("block_position"));
        $this->block->setTemplateId($this->getNumber("block_template"));
        if ($this->hasErrors()) {
            throw new FormException();
        }
    }

}
    