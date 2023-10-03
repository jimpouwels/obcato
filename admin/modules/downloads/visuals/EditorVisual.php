<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . '/modules/downloads/visuals/DownloadMetadataEditor.php';
require_once CMS_ROOT . '/modules/downloads/visuals/DownloadInfo.php';

class EditorVisual extends Visual {

    private Download $_download;

    public function __construct(Download $download) {
        parent::__construct();
        $this->_download = $download;
    }

    public function getTemplateFilename(): string {
        return "modules/downloads/editor.tpl";
    }

    public function load(): void {
        $metadata_editor = new DownloadMetadataEditor($this->_download);
        $download_info = new DownloadInfo($this->_download);
        $this->assign('metadata_editor', $metadata_editor->render());
        $this->assign('download_info', $download_info->render());
    }
}
