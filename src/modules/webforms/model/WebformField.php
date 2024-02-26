<?php

namespace Obcato\Core\modules\webforms\model;

abstract class WebformField extends WebformItem {

    private bool $_mandatory = false;

    public function getMandatory(): bool {
        return $this->_mandatory;
    }

    public function setMandatory(bool $mandatory): void {
        $this->_mandatory = $mandatory;
    }

    protected function initFromDb(array $row): void {
        $this->setMandatory($row["mandatory"] == 1);
        parent::initFromDb($row);
    }

}