<?php

    defined('_ACCESS') or die;

    require_once CMS_ROOT . "database/dao/image_dao.php";

    class ImageMetadataEditor extends Panel {

        private static string $TEMPLATE = "images/images/metadata_editor.tpl";

        private Image $_current_image;

        public function __construct(Image $current_image) {
            parent::__construct('Algemeen', 'image_meta');
            $this->_current_image = $current_image;
        }

        public function render(): string {
            return parent::render();
        }

        public function renderPanelContent(): string {
            $this->assignImageMetaDataFields();
            $this->getTemplateEngine()->assign("current_image_id", $this->_current_image->getId());
            $this->getTemplateEngine()->assign("action_form_id", ACTION_FORM_ID);
            return $this->getTemplateEngine()->fetch("modules/" . self::$TEMPLATE);
        }


        private function assignImageMetaDataFields(): void {
            $title_field = new TextField("image_title", "Titel", $this->_current_image->getTitle(), true, false, null);
            $published_field = new SingleCheckbox("image_published", "Gepubliceerd", $this->_current_image->isPublished(), false, null);
            $upload_field = new UploadField("image_file", "Afbeelding", false, null);

            $this->getTemplateEngine()->assign("image_id", $this->_current_image->getId());
            $this->getTemplateEngine()->assign("title_field", $title_field->render());
            $this->getTemplateEngine()->assign("published_field", $published_field->render());
            $this->getTemplateEngine()->assign("upload_field", $upload_field->render());
        }

    }

?>
