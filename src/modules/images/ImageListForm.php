<?php

namespace Pageflow\Core\modules\images;

use Pageflow\Core\core\form\Form;
use Pageflow\Core\core\form\FormException;

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