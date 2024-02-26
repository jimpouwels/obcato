<?php

namespace Obcato\Core\modules\downloads\visuals;

use Obcato\Core\modules\downloads\model\Download;
use Obcato\Core\view\views\Visual;

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
