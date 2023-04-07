<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/views/visual.php";
    require_once CMS_ROOT . 'modules/downloads/visuals/metadata_editor.php';
    require_once CMS_ROOT . 'modules/downloads/visuals/download_info.php';

    class EditorVisual extends Visual {

        private static $TEMPLATE = "downloads/editor.tpl";
        private $_download;
        private $_template_engine;

        public function __construct($download) {
            parent::__construct();
            $this->_download = $download;
            $this->_template_engine = TemplateEngine::getInstance();
        }

        public function renderVisual(): string {
            $metadata_editor = new DownloadMetadataEditor($this->_download);
            $download_info = new DownloadInfo($this->_download);
            $this->_template_engine->assign('metadata_editor', $metadata_editor->render());
            $this->_template_engine->assign('download_info', $download_info->render());
            return $this->_template_engine->fetch("modules/" . self::$TEMPLATE);
        }
    }
