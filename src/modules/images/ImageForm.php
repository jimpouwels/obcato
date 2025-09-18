<?php

namespace Obcato\Core\modules\images;

use Obcato\Core\core\form\Form;
use Obcato\Core\core\form\FormException;
use Obcato\Core\modules\images\model\Image;

class ImageForm extends Form {

    private Image $image;
    private array $selectedLabels;
    private ?string $newImageLabel;
    private ?int $width = null;
    private ?int $height = null;

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
        $this->width = $this->getNumber("image_width");
        $this->height = $this->getNumber("image_height");
        if ($this->hasErrors()) {
            throw new FormException();
        }
    }

    public function getNewWidth(): ?int {
        return $this->width;
    }

    public function getNewHeight(): ?int {
        return $this->height;
    }

    public function getSelectedLabels(): array {
        return $this->selectedLabels;
    }

    public function getNewImageLabelName(): ?string {
        return $this->newImageLabel;
    }

}
