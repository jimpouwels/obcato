<?php
require_once CMS_ROOT . "/core/form/Form.php";

class ImageListForm extends Form {

    private int $imageId;

    public function loadFields(): void {
        $this->imageId = intval($this->getMandatoryFieldValue("image_id"));
        if ($this->hasErrors()) {
            throw new FormException();
        }
    }

    public function getImageId(): int {
        return $this->imageId;
    }

}
