<?php

    defined('_ACCESS') or die;

    require_once CMS_ROOT . "database/dao/image_dao.php";

    class ImageMetadataEditor extends Panel {

        private static $TEMPLATE = "images/images/metadata_editor.tpl";

        private $_template_engine;
        private $_current_image;

        public function __construct($current_image) {
            parent::__construct('Algemeen', 'image_meta');
            $this->_current_image = $current_image;
            $this->_template_engine = TemplateEngine::getInstance();
        }

        public function render(): string {
            return parent::render();
        }

        public function renderPanelContent() {
            $this->assignImageMetaDataFields();
            $this->_template_engine->assign("current_image_id", $this->_current_image->getId());
            $this->_template_engine->assign("action_form_id", ACTION_FORM_ID);
            return $this->_template_engine->fetch("modules/" . self::$TEMPLATE);
        }


        private function assignImageMetaDataFields() {
            $title_field = new TextField("image_title", "Titel", $this->_current_image->getTitle(), true, false, null);
            $published_field = new SingleCheckbox("image_published", "Gepubliceerd", $this->_current_image->isPublished(), false, null);
            $upload_field = new UploadField("image_file", "Afbeelding", false, null);

            $this->_template_engine->assign("image_id", $this->_current_image->getId());
            $this->_template_engine->assign("title_field", $title_field->render());
            $this->_template_engine->assign("published_field", $published_field->render());
            $this->_template_engine->assign("upload_field", $upload_field->render());
        }

    }

?>
