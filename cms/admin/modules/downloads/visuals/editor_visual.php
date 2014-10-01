<?php

    // No direct access
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "/view/views/visual.php";
    require_once CMS_ROOT . "/database/dao/settings_dao.php";

    class EditorVisual extends Visual {

        private static $TEMPLATE = "downloads/editor.tpl";
        private $_download;
        private $_template_engine;
        private $_settings;

        public function __construct($download) {
            $this->_download = $download;
            $this->_template_engine = TemplateEngine::getInstance();
            $this->_settings = SettingsDao::getInstance()->getSettings();
        }

        public function render() {
            $title_field = new TextField("download_title", "Titel", $this->_download->getTitle(), true, false, null);
            $published_field = new SingleCheckbox("download_published", "Gepubliceerd", $this->_download->isPublished(), false, null);
            $upload_field = new UploadField("download_file", "Bestand", false, null);

            $this->_template_engine->assign("download_id", $this->_download->getId());
            $this->_template_engine->assign("title_field", $title_field->render());
            $this->_template_engine->assign("published_field", $published_field->render());
            $this->_template_engine->assign("upload_field", $upload_field->render());
            $this->_template_engine->assign("file", $this->getFileData());
            return $this->_template_engine->fetch("modules/" . self::$TEMPLATE);
        }

        private function getFileData() {
            $file_path = $this->_settings->getUploadDir() . '/' . $this->_download->getFileName();
            $file_exists = file_exists($file_path);
            $file_data = array();
            $file_data['name'] = $this->_download->getFileName();
            if ($file_exists)
                $file_data['size'] = filesize($file_path) / 1000;
            $file_data['exists'] = $file_exists;
            return $file_data;
        }
    }