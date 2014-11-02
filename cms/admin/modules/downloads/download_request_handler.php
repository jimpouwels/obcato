<?php

    
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "request_handlers/module_request_handler.php";
    require_once CMS_ROOT . "database/dao/download_dao.php";
    require_once CMS_ROOT . "modules/downloads/download_form.php";
    require_once CMS_ROOT . "core/data/download.php";

    class DownloadRequestHandler extends ModuleRequestHandler {

        private $_download_dao;
        private $_current_download;

        public function __construct() {
            $this->_download_dao = DownloadDao::getInstance();
        }

        public function handleGet() {
            $this->_current_download = $this->getDownloadFromGetRequest();
        }

        public function handlePost() {
            $this->_current_download = $this->getDownloadFromPostRequest();
            if ($this->isAddDownloadAction())
                $this->addDownload();
            else if ($this->isDeleteDownloadAction())
                $this->deleteDownload();
            else if ($this->isUpdateDownloadAction())
                $this->updateDownload();
        }

        public function getCurrentDownload() {
            return $this->_current_download;
        }

        public function isSearchAction() {
            return isset($_GET['search_query']);
        }

        public function getSearchQuery() {
            if (isset($_GET['search_query']))
                return $_GET['search_query'];
        }

        private function addDownload() {
            $download = new Download();
            $download->setTitle("Nieuwe download");
            $this->_download_dao->persistDownload($download);
            $this->sendSuccessMessage("Download succesvol toegevoegd");
            $this->redirectTo("/admin/index.php?download=" . $download->getId());
        }

        private function updateDownload() {
            $download_form = new DownloadForm($this->_current_download);
            try {
                $download_form->loadFields();
                $this->saveUploadedFile($download_form);
                $this->_download_dao->updateDownload($this->_current_download);
                $this->sendSuccessMessage("Download succesvol opgeslagen");
            } catch (FormException $e) {
                $this->sendErrorMessage("Download niet opgeslagen, verwerk de fouten");
            }
        }

        private function deleteDownload() {
            $this->deleteDownloadFile($this->_current_download->getFileName());
            $this->_download_dao->deleteDownload($this->_current_download->getId());
            $this->sendSuccessMessage('Download succesvol verwijderd');
            $this->redirectTo('/admin/index.php');
        }

        private function saveUploadedFile($download_form) {
            if ($download_form->getUploadPath()) {
                $new_file_name = $this->getNewDownloadFilename($download_form->getUploadFileName());
                $this->deleteDownloadFile($this->_current_download->getFileName());
                $this->moveDownloadToUploadDirectory($download_form->getUploadPath(), $new_file_name);
            }
        }

        private function moveDownloadToUploadDirectory($from_dir, $new_file_name) {
            rename($from_dir, UPLOAD_DIR . '/' . $new_file_name);
            $this->_current_download->setFileName($new_file_name);
        }

        private function deleteDownloadFile($file_name) {
            if (!$file_name) return;
            $file_path = UPLOAD_DIR . '/' . $file_name;
            if (file_exists($file_path))
                unlink($file_path);
        }

        private function getNewDownloadFilename($download_file_name) {
            $current_download_id = $this->_current_download->getId();
            return "UPLDWNL-00$current_download_id" . "_$download_file_name";
        }

        private function getDownloadFromGetRequest() {
            if (isset($_GET['download']) && $_GET['download'] != '')
                return $this->_download_dao->getDownload($_GET['download']);
        }

        private function getDownloadFromPostRequest() {
            if (isset($_POST['download_id']) && $_POST['download_id'] != '')
                return $this->_download_dao->getDownload($_POST['download_id']);
        }

        private function isAddDownloadAction() {
            return isset($_POST["add_download_action"]) && $_POST["add_download_action"] != "";
        }

        private function isDeleteDownloadAction() {
            return isset($_POST['action']) && $_POST['action'] == 'delete_download';
        }

        private function isUpdateDownloadAction() {
            return isset($_POST['action']) && $_POST['action'] == 'update_download';
        }
    }
