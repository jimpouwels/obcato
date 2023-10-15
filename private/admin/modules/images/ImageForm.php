<?php
require_once CMS_ROOT . "/core/form/Form.php";

class ImageForm extends Form {

    private Image $image;
    private array $selectedLabels;

    public function __construct(Image $image) {
        $this->image = $image;
    }

    public function loadFields(): void {
        $this->image->setTitle($this->getMandatoryFieldValue("image_title"));
        $this->image->setAltText($this->getFieldValue("image_alt_text"));
        $this->image->setPublished($this->getCheckboxValue("image_published"));
        $this->selectedLabels = $this->getSelectValue("select_labels_" . $this->image->getId());
        if ($this->hasErrors()) {
            throw new FormException();
        }
    }

    public function getSelectedLabels(): array {
        return $this->selectedLabels;
    }

}
