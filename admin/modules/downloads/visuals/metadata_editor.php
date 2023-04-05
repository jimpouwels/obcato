<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/views/visual.php";
    require_once CMS_ROOT . "view/views/information_message.php";

    class DownloadMetadataEditor extends Panel {

        private static $TEMPLATE = "downloads/metadata_editor.tpl";
        private $_download;
        private $_template_engine;

        public function __construct($download) {
            parent::__construct('Algemeen');
            $this->_download = $download;
            $this->_template_engine = TemplateEngine::getInstance();
        }

        public function render(): string {
            return parent::render();
        }

        public function renderPanelContent() {
            $title_field = new TextField("download_title", "Titel", $this->_download->getTitle(), true, false, null);
            $published_field = new SingleCheckbox("download_published", "Gepubliceerd", $this->_download->isPublished(), false, null);
            $upload_field = new UploadField("download_file", "Bestand", false, null);

            $this->_template_engine->assign("download_id", $this->_download->getId());
            $this->_template_engine->assign("title_field", $title_field->render());
            $this->_template_engine->assign("published_field", $published_field->render());
            $this->_template_engine->assign("upload_field", $upload_field->render());
            return $this->_template_engine->fetch("modules/" . self::$TEMPLATE);
        }
    }
