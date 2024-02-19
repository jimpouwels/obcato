<?php
require_once CMS_ROOT . "/database/dao/ImageDaoMysql.php";

class ImageMetadataEditor extends Panel {

    private Image $currentImage;

    public function __construct(TemplateEngine $templateEngine, Image $current) {
        parent::__construct($templateEngine, 'Algemeen', 'image_meta');
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
        $titleField = new TextField($this->getTemplateEngine(), "image_title", "Titel", $this->currentImage->getTitle(), true, false, null);
        $altTextField = new TextField($this->getTemplateEngine(), "image_alt_text", $this->getTextResource('image_editor_alt_text_label'), $this->currentImage->getAltText(), false, false, null);
        $publishedField = new SingleCheckbox($this->getTemplateEngine(), "image_published", "Gepubliceerd", $this->currentImage->isPublished(), false, null);
        $uploadField = new UploadField($this->getTemplateEngine(), "image_file", "Afbeelding", false, null);

        $data->assign("image_id", $this->currentImage->getId());
        $data->assign("title_field", $titleField->render());
        $data->assign("alt_text_field", $altTextField->render());
        $data->assign("published_field", $publishedField->render());
        $data->assign("upload_field", $uploadField->render());
    }

}
