<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/views/visual.php";
    require_once CMS_ROOT . "view/views/information_message.php";

    class DownloadMetadataEditor extends Panel {

        private static $TEMPLATE = "downloads/metadata_editor.tpl";
        private $_download;

        public function __construct($download) {
            parent::__construct('Algemeen');
            $this->_download = $download;
        }

        public function renderVisual(): string {
            return parent::renderVisual();
        }

        public function renderPanelContent() {
            $title_field = new TextField("download_title", "Titel", $this->_download->getTitle(), true, false, null);
            $published_field = new SingleCheckbox("download_published", "Gepubliceerd", $this->_download->isPublished(), false, null);
            $upload_field = new UploadField("download_file", "Bestand", false, null);

            $this->getTemplateEngine()->assign("download_id", $this->_download->getId());
            $this->getTemplateEngine()->assign("title_field", $title_field->render());
            $this->getTemplateEngine()->assign("published_field", $published_field->render());
            $this->getTemplateEngine()->assign("upload_field", $upload_field->render());
            return $this->getTemplateEngine()->fetch("modules/" . self::$TEMPLATE);
        }
    }
