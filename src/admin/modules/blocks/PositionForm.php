<?php

namespace Obcato\Core\admin\modules\blocks;

use Obcato\Core\admin\core\form\Form;
use Obcato\Core\admin\core\form\FormException;
use Obcato\Core\admin\database\dao\BlockDao;
use Obcato\Core\admin\database\dao\BlockDaoMysql;
use Obcato\Core\admin\modules\blocks\model\BlockPosition;

class PositionForm extends Form {

    private BlockPosition $position;
    private BlockDao $blockDao;

    public function __construct(BlockPosition $position) {
        $this->position = $position;
        $this->blockDao = BlockDaoMysql::getInstance();
    }

    public function loadFields(): void {
        $positionName = str_replace(" ", "_", $this->getMandatoryFieldValue("name"));
        $this->position->setName($positionName);
        $this->position->setExplanation($this->getFieldValue("explanation"));
        if ($this->hasErrors() || $this->positionAlreadyExists()) {
            throw new FormException();
        }
    }

    private function positionAlreadyExists(): bool {
        $existingPos = $this->blockDao->getBlockPositionByName($this->position->getName());
        if ($existingPos && $existingPos->getId() != $this->position->getId()) {
            $this->raiseError("name", "Er bestaat al een positie met deze naam");
            return true;
        }
        return false;
    }

}
