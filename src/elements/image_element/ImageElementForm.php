<?php

namespace Obcato\Core\elements\image_element;

use Obcato\Core\core\form\FormException;
use Obcato\Core\request_handlers\ElementForm;

class ImageElementForm extends ElementForm {

    private ImageElement $imageElement;

    public function __construct(ImageElement $imageElement) {
        parent::__construct($imageElement);
        $this->imageElement = $imageElement;
    }

    public function loadFields(): void {
        parent::loadFields();
        $this->imageElement->setTitle($this->getFieldValue('element_' . $this->imageElement->getId() . '_title'));
        $this->imageElement->setAlign($this->getFieldValue('element_' . $this->imageElement->getId() . '_align'));
        $this->imageElement->setImageId($this->getNumber('image_image_ref_' . $this->imageElement->getId()));
        $this->imageElement->setWidth($this->getNumber('element_' . $this->imageElement->getId() . '_width'));
        $this->imageElement->setHeight($this->getNumber('element_' . $this->imageElement->getId() . '_height'));
        $this->imageElement->setLinkId($this->getNumber('element_' . $this->imageElement->getId() . '_link'));
        if ($this->hasErrors()) {
            throw new FormException();
        }
    }

}