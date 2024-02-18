<?php
require_once CMS_ROOT . '/modules/downloads/visuals/DownloadMetadataEditor.php';
require_once CMS_ROOT . '/modules/downloads/visuals/DownloadInfo.php';

class EditorVisual extends Visual {

    private Download $download;

    public function __construct(Download $download) {
        parent::__construct();
        $this->download = $download;
    }

    public function getTemplateFilename(): string {
        return "modules/downloads/editor.tpl";
    }

    public function load(): void {
        $metadataEditor = new DownloadMetadataEditor($this->download);
        $downloadInfo = new DownloadInfo($this->download);
        $this->assign('metadata_editor', $metadataEditor->render());
        $this->assign('download_info', $downloadInfo->render());
    }
}
