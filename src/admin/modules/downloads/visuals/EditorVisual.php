<?php

namespace Obcato\Core\admin\modules\downloads\visuals;

use Obcato\ComponentApi\TemplateEngine;
use Obcato\Core\admin\modules\downloads\model\Download;
use Obcato\Core\admin\view\views\Visual;

class EditorVisual extends Visual {

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
