<?php

class DownloadInfo extends Panel {

    private Download $download;

    public function __construct(TemplateEngine $templateEngine, Download $download) {
        parent::__construct($templateEngine, 'Bestandsinformatie');
        $this->download = $download;
    }

    public function getPanelContentTemplate(): string {
        return "modules/downloads/download_info.tpl";
    }

    public function loadPanelContent(Smarty_Internal_Data $data): void {
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