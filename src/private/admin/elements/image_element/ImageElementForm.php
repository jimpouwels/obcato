<?php

namespace Obcato\Core;
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
        if ($this->hasErrors()) {
            throw new FormException();
        }
    }

}