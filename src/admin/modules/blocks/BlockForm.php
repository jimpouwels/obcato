<?php

namespace Obcato\Core\admin\modules\blocks;

use Obcato\Core\admin\core\form\Form;
use Obcato\Core\admin\core\form\FormException;
use Obcato\Core\admin\modules\blocks\model\Block;

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
    