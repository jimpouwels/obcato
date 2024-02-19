<?php
require_once CMS_ROOT . '/modules/downloads/visuals/DownloadMetadataEditor.php';
require_once CMS_ROOT . '/modules/downloads/visuals/DownloadInfo.php';

class EditorVisual extends Obcato\ComponentApi\Visual {

    private Download $download;

    public function __construct(TemplateEngine $templateEngine, Download $download) {
        parent::__construct($templateEngine);
        $this->download = $download;
    }

    public function getTemplateFilename(): string {
        return "modules/downloads/editor.tpl";
    }

    public function load(): void {
        $metadataEditor = new DownloadMetadataEditor($this->getTemplateEngine(), $this->download);
        $downloadInfo = new DownloadInfo($this->getTemplateEngine(), $this->download);
        $this->assign('metadata_editor', $metadataEditor->render());
        $this->assign('download_info', $downloadInfo->render());
    }
}
