<?php

    defined("_ACCESS") or die;

    require_once CMS_ROOT . "request_handlers/form.php";

    class DownloadForm extends Form {

        private $_download;

        public function __construct($download) {
            $this->_download = $download;
        }

        public function loadFields() {
            $this->_download->setTitle($this->getMandatoryFieldValue("download_title", "Titel is verplicht"));
            $this->_download->setPublished($this->getCheckboxValue("download_published"));
            if ($this->hasErrors())
                throw new FormException();
        }

        public function getUploadPath() {
            return $this->getUploadFilePath('download_file');
        }

        public function getUploadFileName() {
            return $this->getUploadedFileName('download_file');
        }

    }
    