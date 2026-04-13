<?php

namespace Obcato\Core\modules\images;

use Obcato\Core\core\form\Form;
use Obcato\Core\core\form\FormException;
use Obcato\Core\modules\images\model\Image;
use Obcato\Core\utilities\ImageUtility;
use const Obcato\Core\UPLOAD_DIR;

class ImageForm extends Form {

    private Image $image;
    private ?int $newWidth = null;
    private ?int $newMobileWidth = null;
    private ?int $newHeight = null;
    private ?int $newMobileHeight = null;
    private ?int $cropTop = null;
    private ?int $cropMobileTop = null;
    private ?int $cropBottom = null;
    private ?int $cropMobileBottom = null;
    private ?int $cropLeft = null;
    private ?int $cropMobileLeft = null;
    private ?int $cropRight = null;
    private ?int $cropMobileRight = null;

    public function __construct(Image $image) {
        $this->image = $image;
    }

    public function loadFields(): void {
        $this->image->setTitle($this->getMandatoryFieldValue("image_title"));
        $this->image->setAltText($this->getFieldValue("image_alt_text"));
        $this->image->setPublished($this->getCheckboxValue("image_published"));
        $this->image->setLocation($this->getFieldValue("image_location"));

        if ($this->image->getFilename()) {
            $this->validateDesktopEditorFields();
        }
        if (ImageUtility::exists($this->image->getMobileFilename())) {
            $this->validateMobileEditorFields();
        }
        if ($this->hasErrors()) {
            throw new FormException();
        }
    }

    public function getNewWidth(): ?int {
        return $this->newWidth;
    }

    public function getNewMobileWidth(): ?int {
        return $this->newMobileWidth;
    }

    public function getNewHeight(): ?int {
        return $this->newHeight;
    }

    public function getNewMobileHeight(): ?int {
        return $this->newMobileHeight;
    }

    public function getCropTop(): ?int {
        return $this->cropTop;
    }

    public function getCropMobileTop(): ?int {
        return $this->cropMobileTop;
    }

    public function getCropBottom(): ?int {
        return $this->cropBottom;
    }

    public function getCropMobileBottom(): ?int {
        return $this->cropMobileBottom;
    }

    public function getCropLeft(): ?int {
        return $this->cropLeft;
    }

    public function getCropMobileLeft(): ?int {
        return $this->cropMobileLeft;
    }

    public function getCropRight(): ?int {
        return $this->cropRight;
    }

    public function getCropMobileRight(): ?int {
        return $this->cropMobileRight;
    }

    private function validateDesktopEditorFields(): void {
        if (!ImageUtility::exists($this->image->getFilename())) {
            return;
        }
        $imageObj = ImageUtility::loadImage($this->image->getFilename());
        $imageWidth = $imageObj->getImageWidth();
        $imageHeight = $imageObj->getImageHeight();

        $this->newWidth = $this->getNumber("image_new_width");
        $this->newHeight = $this->getNumber("image_new_height");

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
        if ($this->cropTop + $this->cropBottom > $imageHeight) {
            $this->raiseError('image_crop_top', 'image_crop_cannot_be_bigger_than_height');
        }
        if ($this->cropLeft + $this->cropRight > $imageWidth) {
            $this->raiseError('image_crop_left', 'image_crop_cannot_be_bigger_than_width');
        }
    }

    private function validateMobileEditorFields(): void {
        $imageObj = ImageUtility::loadImage($this->image->getMobileFilename());
        $imageWidth = $imageObj->getImageWidth();
        $imageHeight = $imageObj->getImageHeight();

        $this->newMobileWidth = $this->getNumber("image_mobile_new_width");
        $this->newMobileHeight = $this->getNumber("image_mobile_new_height");

        if ($this->newMobileWidth > $imageWidth) {
            $this->raiseError('image_mobile_new_width', 'image_resize_loses_precision');
        }
        if ($this->newMobileHeight > $imageHeight) {
            $this->raiseError('image_mobile_new_height', 'image_resize_loses_precision');
        }
        $this->cropMobileTop = $this->getNumber("image_mobile_crop_top");
        $this->cropMobileBottom = $this->getNumber("image_mobile_crop_bottom");
        $this->cropMobileLeft = $this->getNumber("image_mobile_crop_left");
        $this->cropMobileRight = $this->getNumber("image_mobile_crop_right");
        if ($this->cropMobileTop < 0) {
            $this->raiseError('image_mobile_crop_top', 'image_crop_cannot_be_negative');
        }
        if ($this->cropMobileBottom < 0) {
            $this->raiseError('image_mobile_crop_bottom', 'image_crop_cannot_be_negative');
        }
        if ($this->cropMobileLeft < 0) {
            $this->raiseError('image_mobile_crop_left', 'image_crop_cannot_be_negative');
        }
        if ($this->cropMobileRight < 0) {
            $this->raiseError('image_mobile_crop_right', 'image_crop_cannot_be_negative');
        }
        if ($this->cropMobileTop + $this->cropMobileBottom > $imageHeight) {
            $this->raiseError('image_mobile_crop_top', 'image_crop_cannot_be_bigger_than_height');
        }
        if ($this->cropMobileLeft + $this->cropMobileRight > $imageWidth) {
            $this->raiseError('image_mobile_crop_left', 'image_crop_cannot_be_bigger_than_width');
        }
    }

}
