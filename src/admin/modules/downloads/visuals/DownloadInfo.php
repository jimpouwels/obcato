<?php

namespace Obcato\Core\admin\modules\downloads\visuals;

use Obcato\ComponentApi\TemplateData;
use Obcato\ComponentApi\TemplateEngine;
use Obcato\Core\admin\modules\downloads\model\Download;
use Obcato\Core\admin\view\views\Panel;

class DownloadInfo extends Panel {

    private Download $download;

    public function __construct(TemplateEngine $templateEngine, Download $download) {
        parent::__construct($templateEngine, 'Bestandsinformatie');
        $this->download = $download;
    }

    public function getPanelContentTemplate(): string {
        return "modules/downloads/download_info.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $data->assign("file", $this->getFileData());
    }

    private function getFileData(): array {
        $filePath = UPLOAD_DIR . '/' . $this->download->getFilename();
        $fileExists = file_exists($filePath);
        $fileData = array();
        $fileData['name'] = $this->download->getFilename();
        if ($fileExists) {
            $fileData['size'] = filesize($filePath) / 1000;
        }
        $fileData['exists'] = $fileExists;
        return $fileData;
    }
}
