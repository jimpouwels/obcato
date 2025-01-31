<?php

namespace Obcato\Core\modules\images;

use Obcato\Core\core\form\Form;
use Obcato\Core\core\form\FormException;
use Obcato\Core\modules\images\model\Image;

class ImageForm extends Form {

    private Image $image;
    private array $selectedLabels;
    private ?string $newImageLabel;

    public function __construct(Image $image) {
        $this->image = $image;
    }

    public function loadFields(): void {
        $this->image->setTitle($this->getMandatoryFieldValue("image_title"));
        $this->image->setAltText($this->getFieldValue("image_alt_text"));
        $this->image->setPublished($this->getCheckboxValue("image_published"));
        $this->image->setLocation($this->getFieldValue("image_location"));
        $this->newImageLabel = $this->getFieldValue("new_image_label_" . $this->image->getId());
        $this->selectedLabels = $this->getSelectValue("select_labels_" . $this->image->getId());
        if ($this->hasErrors()) {
            throw new FormException();
        }
    }

    public function getSelectedLabels(): array {
        return $this->selectedLabels;
    }

    public function getNewImageLabelName(): ?string {
        return $this->newImageLabel;
    }

}
