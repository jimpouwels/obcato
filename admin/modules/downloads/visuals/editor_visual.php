<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/views/visual.php";
    require_once CMS_ROOT . 'modules/downloads/visuals/metadata_editor.php';
    require_once CMS_ROOT . 'modules/downloads/visuals/download_info.php';

    class EditorVisual extends Visual {

        private static $TEMPLATE = "downloads/editor.tpl";
        private $_download;

        public function __construct($download) {
            parent::__construct();
            $this->_download = $download;
        }

        public function renderVisual(): string {
            $metadata_editor = new DownloadMetadataEditor($this->_download);
            $download_info = new DownloadInfo($this->_download);
            $this->getTemplateEngine()->assign('metadata_editor', $metadata_editor->render());
            $this->getTemplateEngine()->assign('download_info', $download_info->render());
            return $this->getTemplateEngine()->fetch("modules/" . self::$TEMPLATE);
        }
    }
