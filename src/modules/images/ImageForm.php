<?php

namespace Obcato\Core\modules\images;

use Obcato\Core\core\form\Form;
use Obcato\Core\core\form\FormException;
use Obcato\Core\modules\images\model\Image;
use const Obcato\Core\UPLOAD_DIR;

class ImageForm extends Form {

    private Image $image;
    private array $selectedLabels;
    private ?string $newImageLabel;
    private ?int $newWidth = null;
    private ?int $newHeight = null;
    private ?int $cropTop = null;
    private ?int $cropBottom = null;
    private ?int $cropLeft = null;
    private ?int $cropRight = null;

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

        $imageObj = imagecreatefromwebp(UPLOAD_DIR . "/" . $this->image->getFilename());
        $imageWidth = imagesx($imageObj);
        $imageHeight = imagesy($imageObj);

        $this->newWidth = $this->getNumber("image_new_width");
        $this->newHeight = $this->getNumber("image_new_height");

        $resultWidth = $imageWidth;
        $resultHeight = $imageHeight;
        if ($this->newWidth) {
            $resultWidth = $this->newWidth;
        }
        if ($this->newHeight) {
            $resultHeight = $this->newHeight;
        }

        if ($this->newWidth > $imageWidth) {
            $this->raiseError('image_new_width', 'image_resize_loses_precision');
        }
        if ($this->newHeight > $imageHeight) {
            $this->raiseError('image_new_height', 'image_resize_loses_precision');
        }
        $this->cropTop = $this->getNumber("image_crop_top");
        $this->cropBottom = $this->getNumber("image_crop_bottom");
        $this->cropLeft = $this->getNumber("image_crop_left");
        $this->cropRight = $this->getNumber("image_crop_right");
        if ($this->cropTop < 0) {
            $this->raiseError('image_crop_top', 'image_crop_cannot_be_negative');
        }
        if ($this->cropBottom < 0) {
            $this->raiseError('image_crop_bottom', 'image_crop_cannot_be_negative');
        }
        if ($this->cropLeft < 0) {
            $this->raiseError('image_crop_left', 'image_crop_cannot_be_negative');
        }
        if ($this->cropRight < 0) {
            $this->raiseError('image_crop_right', 'image_crop_cannot_be_negative');
        }
        if ($this->cropTop + $this->cropBottom > $resultHeight) {
            $this->raiseError('image_crop_top', 'image_crop_cannot_be_bigger_than_height');
        }
        if ($this->cropLeft + $this->cropRight > $resultWidth) {
            $this->raiseError('image_crop_left', 'image_crop_cannot_be_bigger_than_width');
        }
        if ($this->hasErrors()) {
            throw new FormException();
        }
    }

    public function getNewWidth(): ?int {
        return $this->newWidth;
    }

    public function getNewHeight(): ?int {
        return $this->newHeight;
    }

    public function getCropTop(): ?int {
        return $this->cropTop;
    }

    public function getCropBottom(): ?int {
        return $this->cropBottom;
    }

    public function getCropLeft(): ?int {
        return $this->cropLeft;
    }

    public function getCropRight(): ?int {
        return $this->cropRight;
    }

    public function getSelectedLabels(): array {
        return $this->selectedLabels;
    }

    public function getNewImageLabelName(): ?string {
        return $this->newImageLabel;
    }

}
