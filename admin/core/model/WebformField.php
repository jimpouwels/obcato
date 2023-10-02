<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/core/model/WebformItem.php";

abstract class WebFormField extends WebFormItem {

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