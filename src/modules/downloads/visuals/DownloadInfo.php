<?php

namespace Pageflow\Core\modules\downloads\visuals;

use Pageflow\Core\modules\downloads\model\Download;
use Pageflow\Core\view\TemplateData;
use Pageflow\Core\view\views\Panel;
use const Pageflow\core\STATIC_DIR;

class DownloadInfo extends Panel {

    private Download $download;

    public function __construct(Download $download) {
        parent::__construct('Bestandsinformatie');
        $this->download = $download;
    }

    public function getPanelContentTemplate(): string {
        return "downloads/templates/download_info.tpl";
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
