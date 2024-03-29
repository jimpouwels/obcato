<?php

namespace Obcato\Core\modules\images\visuals\images;

use Obcato\Core\modules\images\model\Image;
use Obcato\Core\view\TemplateData;
use Obcato\Core\view\views\Panel;
use Obcato\Core\view\views\SingleCheckbox;
use Obcato\Core\view\views\TextField;
use Obcato\Core\view\views\UploadField;
use const Obcato\Core\ACTION_FORM_ID;

class ImageMetadataEditor extends Panel {

    private Image $currentImage;

    public function __construct(Image $current) {
        parent::__construct('Algemeen', 'image_meta');
        $this->currentImage = $current;
    }

    public function getPanelContentTemplate(): string {
        return "modules/images/images/metadata_editor.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $this->assignImageMetaDataFields($data);
        $data->assign("current_image_id", $this->currentImage->getId());
        $data->assign("action_form_id", ACTION_FORM_ID);
    }


    private function assignImageMetaDataFields($data): void {
        $titleField = new TextField("image_title", "Titel", $this->currentImage->getTitle(), true, false, null);
        $altTextField = new TextField("image_alt_text", $this->getTextResource('image_editor_alt_text_label'), $this->currentImage->getAltText(), false, false, null);
        $publishedField = new SingleCheckbox("image_published", "Gepubliceerd", $this->currentImage->isPublished(), false, null);
        $uploadField = new UploadField("image_file", "Afbeelding", false, null);

        $data->assign("image_id", $this->currentImage->getId());
        $data->assign("title_field", $titleField->render());
        $data->assign("alt_text_field", $altTextField->render());
        $data->assign("published_field", $publishedField->render());
        $data->assign("upload_field", $uploadField->render());
    }

}
