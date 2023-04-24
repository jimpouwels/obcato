<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/views/visual.php";
    require_once CMS_ROOT . "view/views/information_message.php";

    class DownloadInfo extends Panel {

        private Download $_download;

        public function __construct(Download $download) {
            parent::__construct('Bestandsinformatie');
            $this->_download = $download;
        }

        public function getPanelContentTemplate(): string {
            return "modules/downloads/download_info.tpl";
        }

        public function loadPanelContent(Smarty_Internal_Data $data): void {
            $data->assign("file", $this->getFileData());
        }

        private function getFileData(): array {
            $file_path = UPLOAD_DIR . '/' . $this->_download->getFileName();
            $file_exists = file_exists($file_path);
            $file_data = array();
            $file_data['name'] = $this->_download->getFileName();
            if ($file_exists) {
                $file_data['size'] = filesize($file_path) / 1000;
            }
            $file_data['exists'] = $file_exists;
            return $file_data;
        }
    }
