<?php

namespace Obcato\Core\elements\photo_album_element;

use Obcato\Core\core\form\FormException;
use Obcato\Core\request_handlers\ElementForm;

class PhotoAlbumElementForm extends ElementForm {

    private PhotoAlbumElement $photoAlbumElement;

    public function __construct(PhotoAlbumElement $photoAlbumElement) {
        parent::__construct($photoAlbumElement);
        $this->photoAlbumElement = $photoAlbumElement;
    }

    public function loadFields(): void {
        $elementId = $this->photoAlbumElement->getId();
        $title = $this->getFieldValue('element_' . $elementId . '_title');
        $numberOfResults = $this->getNumber('element_' . $elementId . '_number_of_results');
        if ($this->hasErrors())
            throw new FormException();
        else {
            parent::loadFields();
            $this->photoAlbumElement->setTitle($title);
            $this->photoAlbumElement->setNumberOfResults($numberOfResults);
        }
    }
}