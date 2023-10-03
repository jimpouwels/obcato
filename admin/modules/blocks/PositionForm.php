<?php

defined("_ACCESS") or die;

require_once CMS_ROOT . "/core/form/Form.php";

class PositionForm extends Form {

    private BlockPosition $_position;
    private BlockDao $_block_dao;

    public function __construct(BlockPosition $position) {
        $this->_position = $position;
        $this->_block_dao = BlockDaoMysql::getInstance();
    }

    public function loadFields(): void {
        $positionName = str_replace(" ", "_", $this->getMandatoryFieldValue("name"));
        $this->_position->setName($positionName);
        $this->_position->setExplanation($this->getFieldValue("explanation"));
        if ($this->hasErrors() || $this->positionAlreadyExists()) {
            throw new FormException();
        }
    }

    private function positionAlreadyExists(): bool {
        $existing_pos = $this->_block_dao->getBlockPositionByName($this->_position->getName());
        if (!is_null($existing_pos) && $existing_pos->getId() != $this->_position->getId()) {
            $this->raiseError("name", "Er bestaat al een positie met deze naam");
            return true;
        }
        return false;
    }

}
    