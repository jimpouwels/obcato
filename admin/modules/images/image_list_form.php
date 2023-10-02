<?php

defined("_ACCESS") or die;

require_once CMS_ROOT . "/core/form/form.php";

class ImageListForm extends Form {

    private int $_image_id;

    public function loadFields(): void {
        $this->_image_id = intval($this->getMandatoryFieldValue("image_id", ""));
        if ($this->hasErrors()) {
            throw new FormException();
        }
    }

    public function getImageId(): int {
        return $this->_image_id;
    }

}
