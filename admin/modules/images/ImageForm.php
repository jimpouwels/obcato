<?php

defined("_ACCESS") or die;

require_once CMS_ROOT . "/core/form/Form.php";

class ImageForm extends Form {

    private Image $_image;
    private array $_selected_labels;

    public function __construct(Image $image) {
        $this->_image = $image;
    }

    public function loadFields(): void {
        $this->_image->setTitle($this->getMandatoryFieldValue("image_title", "Titel is verplicht"));
        $this->_image->setAltText($this->getFieldValue("image_alt_text"));
        $this->_image->setPublished($this->getCheckboxValue("image_published"));
        $this->_selected_labels = $this->getSelectValue("select_labels_" . $this->_image->getId());
        if ($this->hasErrors()) {
            throw new FormException();
        }
    }

    public function getSelectedLabels(): array {
        return $this->_selected_labels;
    }

}
