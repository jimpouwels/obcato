<?php

namespace Obcato\Core\modules\images\visuals\images;

use Obcato\Core\modules\images\model\Image;
use Obcato\Core\view\TemplateData;
use Obcato\Core\view\views\Panel;
use Obcato\Core\view\views\ReadonlyTextField;
use Obcato\Core\view\views\SingleCheckbox;
use Obcato\Core\view\views\TextField;
use Obcato\Core\view\views\UploadField;
use const Obcato\Core\ACTION_FORM_ID;
use const Obcato\Core\UPLOAD_DIR;

class ImageMetadataEditor extends Panel {

    private Image $currentImage;

    public function __construct(Image $current) {
        parent::__construct('Algemeen', 'image_meta');
        $this->currentImage = $current;
    }

    public function getPanelContentTemplate(): string {
        return "images/templates/images/metadata_editor.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $this->assignImageMetaDataFields($data);
        $data->assign("current_image_id", $this->currentImage->getId());
        $data->assign("action_form_id", ACTION_FORM_ID);
    }


    private function assignImageMetaDataFields(TemplateData $data): void {
        $titleField = new TextField("image_title", $this->getTextResource('image_editor_title_label'), $this->currentImage->getTitle(), true, false, null);
        $altTextField = new TextField("image_alt_text", $this->getTextResource('image_editor_alt_text_label'), $this->currentImage->getAltText(), false, false, null);
        $locationField = new TextField("image_location", $this->getTextResource("image_editor_location_label"), $this->currentImage->getLocation(), false, false, null);
        $publishedField = new SingleCheckbox("image_published", $this->getTextResource('image_editor_published_label'), $this->currentImage->isPublished(), false, null);
        $uploadField = new UploadField("image_file", $this->getTextResource('image_editor_file_label'), false, null);

        $sizeField = null;
        $newWidthField = null;
        $newHeightField = null;
        $cropTopField = null;
        $cropBottomField = null;
        $cropLeftField = null;
        $cropRightField = null;
        if ($this->currentImage->getFilename()) {
            $imageObj = imagecreatefromwebp(UPLOAD_DIR . '/' . $this->currentImage->getFilename());
            $width = imagesx($imageObj);
            $height = imagesy($imageObj);
            $sizeField = new ReadonlyTextField("image_size", $this->getTextResource("image_editor_size_label"), $width . ' x ' . $height, false);
            $newWidthField = new TextField("image_new_width", $this->getTextResource('image_editor_new_width_label'), "", false, false, "");
            $newHeightField = new TextField("image_new_height", $this->getTextResource('image_editor_new_height_label'), "", false, false, "");
            $cropTopField = new TextField("image_crop_top", $this->getTextResource('image_editor_crop_top'), 0, false, false, "");
            $cropBottomField = new TextField("image_crop_bottom", $this->getTextResource('image_editor_crop_bottom'), 0, false, false, "");
            $cropLeftField = new TextField("image_crop_left", $this->getTextResource('image_editor_crop_left'), 0, false, false, "");
            $cropRightField = new TextField("image_crop_right", $this->getTextResource('image_editor_crop_right'), 0, false, false, "");
        }

        $data->assign("image_id", $this->currentImage->getId());
        $data->assign("title_field", $titleField->render());
        $data->assign("alt_text_field", $altTextField->render());
        $data->assign("location_field", $locationField->render());
        $data->assign("published_field", $publishedField->render());
        $data->assign("upload_field", $uploadField->render());
        $data->assignVisual("size_field", $sizeField);
        $data->assignVisual("new_width_field", $newWidthField);
        $data->assignVisual("new_height_field", $newHeightField);
        $data->assignVisual("crop_top_field", $cropTopField);
        $data->assignVisual("crop_bottom_field", $cropBottomField);
        $data->assignVisual("crop_left_field", $cropLeftField);
        $data->assignVisual("crop_right_field", $cropRightField);
    }

}
