<?php

namespace Obcato\Core\modules\blocks;

use Obcato\Core\core\form\Form;
use Obcato\Core\core\form\FormException;
use Obcato\Core\modules\blocks\model\Block;

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
    