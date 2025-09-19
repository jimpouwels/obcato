<?php

namespace Obcato\Core\modules\images\visuals\images;

use Obcato\Core\modules\images\model\Image;
use Obcato\Core\utilities\ImageUtility;
use Obcato\Core\view\TemplateData;
use Obcato\Core\view\views\Panel;
use Obcato\Core\view\views\ReadonlyTextField;
use Obcato\Core\view\views\SingleCheckbox;
use Obcato\Core\view\views\TextField;
use Obcato\Core\view\views\UploadField;
use const Obcato\Core\ACTION_FORM_ID;
use const Obcato\Core\UPLOAD_DIR;

class ImageDesktopEditor extends Panel {

    private Image $currentImage;

    public function __construct(Image $current) {
        parent::__construct('Desktop Editor');
        $this->currentImage = $current;
    }

    public function getPanelContentTemplate(): string {
        return "images/templates/images/image_editor.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $this->assignImageMetaDataFields($data);
    }


    private function assignImageMetaDataFields(TemplateData $data): void {
        $sizeField = null;
        $newWidthField = null;
        $newHeightField = null;
        $cropTopField = null;
        $cropBottomField = null;
        $cropLeftField = null;
        $cropRightField = null;
        $cropVerticalCenterField = null;
        $cropHorizontalCenterField = null;
        $url = null;
        if ($this->currentImage->getFilename()) {
            $imageObj = ImageUtility::loadImage($this->currentImage->getFilename());
            $sizeField = new ReadonlyTextField("image_size", $this->getTextResource("image_editor_size_label"), imagesx($imageObj) . ' x ' . imagesy($imageObj), false);
            $newWidthField = new TextField("image_new_width", $this->getTextResource('image_editor_new_width_label'), "", false, false, "");
            $newHeightField = new TextField("image_new_height", $this->getTextResource('image_editor_new_height_label'), "", false, false, "");
            $cropTopField = new TextField("image_crop_top", $this->getTextResource('image_editor_crop_top'), 0, false, false, "");
            $cropBottomField = new TextField("image_crop_bottom", $this->getTextResource('image_editor_crop_bottom'), 0, false, false, "");
            $cropLeftField = new TextField("image_crop_left", $this->getTextResource('image_editor_crop_left'), 0, false, false, "");
            $cropRightField = new TextField("image_crop_right", $this->getTextResource('image_editor_crop_right'), 0, false, false, "");
            $cropVerticalCenterField = new TextField("image_crop_vertical_center", $this->getTextResource('image_editor_crop_vertical_center'), 0, false, false, "");
            $cropHorizontalCenterField = new TextField("image_crop_horizontal_center", $this->getTextResource('image_editor_crop_horizontal_center'), 0, false, false, "");
            $url = $this->currentImage->getUrl();
        }
        $data->assignVisual("size_field", $sizeField);
        $data->assignVisual("new_width_field", $newWidthField);
        $data->assignVisual("new_height_field", $newHeightField);
        $data->assignVisual("crop_top_field", $cropTopField);
        $data->assignVisual("crop_bottom_field", $cropBottomField);
        $data->assignVisual("crop_left_field", $cropLeftField);
        $data->assignVisual("crop_right_field", $cropRightField);
        $data->assignVisual("crop_vertical_center_field", $cropVerticalCenterField);
        $data->assignVisual("crop_horizontal_center_field", $cropHorizontalCenterField);
        $data->assign("url", $url);
        $data->assign("title", $this->currentImage->getTitle());
    }
}
