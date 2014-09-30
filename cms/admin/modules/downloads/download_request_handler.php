<?php

    // No direct access
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "/libraries/system/notifications.php";
    require_once CMS_ROOT . "/view/request_handlers/module_request_handler.php";
    require_once CMS_ROOT . "/database/dao/download_dao.php";
    require_once CMS_ROOT . "/core/data/download.php";

    class DownloadRequestHandler extends ModuleRequestHandler {

        private $_download_dao;

        public function __construct() {
            $this->_download_dao = DownloadDao::getInstance();
        }

        public function handleGet() {
        }

        public function handlePost() {
            if ($this->isAddDownloadAction())
                $this->addDownload();
        }

        private function addDownload() {
            $download = new Download();
            $download->setTitle("Nieuwe download");
            $this->_download_dao->persistDownload($download);
            Notifications::setSuccessMessage("Download succesvol verwijderd");
            header("Location: /admin/index.php?download=" . $download->getId());
            exit();
        }

        private function isAddDownloadAction() {
            return isset($_POST["add_download_action"]) && $_POST["add_download_action"] != "";
        }
    }