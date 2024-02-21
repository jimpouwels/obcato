<?php

namespace Obcato\Core\admin\elements\photo_album_element;

use Obcato\Core\admin\core\form\FormException;
use Obcato\Core\admin\request_handlers\ElementForm;

class PhotoAlbumElementForm extends ElementForm {

    private PhotoAlbumElement $photoAlbumElement;
    private array $selectedLabels;
    private array $removedLabels;

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

        $this->selectedLabels = $this->getFieldValues('select_labels_' . $this->photoAlbumElement->getId());
        $this->removedLabels = $this->getLabelsToDeleteFromPostRequest();
    }

    public function getSelectedLabels(): array {
        return $this->selectedLabels;
    }

    public function getLabelsToRemove(): array {
        return $this->removedLabels;
    }

    private function getLabelsToDeleteFromPostRequest(): array {
        $labelsToRemove = array();
        $elementLabels = $this->photoAlbumElement->getLabels();
        foreach ($elementLabels as $elementLabel) {
            if (isset($_POST['label_' . $this->photoAlbumElement->getId() . '_' . $elementLabel->getId() . '_delete'])) {
                $labelsToRemove[] = $elementLabel;
            }
        }
        return $labelsToRemove;
    }

}