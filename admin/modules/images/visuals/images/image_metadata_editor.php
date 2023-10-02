<?php

defined('_ACCESS') or die;

require_once CMS_ROOT . "database/dao/image_dao.php";

class ImageMetadataEditor extends Panel {

    private Image $_current_image;

    public function __construct(Image $current_image) {
        parent::__construct('Algemeen', 'image_meta');
        $this->_current_image = $current_image;
    }

    public function getPanelContentTemplate(): string {
        return "modules/images/images/metadata_editor.tpl";
    }

    public function loadPanelContent(Smarty_Internal_Data $data): void {
        $this->assignImageMetaDataFields($data);
        $data->assign("current_image_id", $this->_current_image->getId());
        $data->assign("action_form_id", ACTION_FORM_ID);
    }


    private function assignImageMetaDataFields($data): void {
        $title_field = new TextField("image_title", "Titel", $this->_current_image->getTitle(), true, false, null);
        $alt_text_field = new TextField("image_alt_text", $this->getTextResource('image_editor_alt_text_label'), $this->_current_image->getAltText(), false, false, null);
        $published_field = new SingleCheckbox("image_published", "Gepubliceerd", $this->_current_image->isPublished(), false, null);
        $upload_field = new UploadField("image_file", "Afbeelding", false, null);

        $data->assign("image_id", $this->_current_image->getId());
        $data->assign("title_field", $title_field->render());
        $data->assign("alt_text_field", $alt_text_field->render());
        $data->assign("published_field", $published_field->render());
        $data->assign("upload_field", $upload_field->render());
    }

}

?>
