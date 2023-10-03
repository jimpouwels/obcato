<?php

defined("_ACCESS") or die;

require_once CMS_ROOT . "/core/form/Form.php";
require_once CMS_ROOT . "/database/dao/ImageDaoMysql.php";

class LabelForm extends Form {

    private ImageLabel $_label;
    private ImageDao $_image_dao;

    public function __construct(ImageLabel $label) {
        $this->_label = $label;
        $this->_image_dao = ImageDaoMysql::getInstance();
    }

    public function loadFields(): void {
        $this->_label->setName($this->getMandatoryFieldValue("name"));
        if ($this->hasErrors() || $this->labelExists())
            throw new FormException();
    }

    private function labelExists(): bool {
        $existing_label = $this->_image_dao->getLabelByName($this->_label->getName());
        if (!is_null($existing_label) && $existing_label->getId() != $this->_label->getId()) {
            $this->raiseError("name", "Er bestaat al een label met deze naam");
            return true;
        }
        return false;
    }

}
    