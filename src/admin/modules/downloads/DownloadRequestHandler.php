<?php

namespace Obcato\Core\admin\modules\downloads;


use Obcato\Core\admin\core\form\FormException;
use Obcato\Core\admin\database\dao\DownloadDao;
use Obcato\Core\admin\database\dao\DownloadDaoMysql;
use Obcato\Core\admin\modules\downloads\model\Download;
use Obcato\Core\admin\request_handlers\HttpRequestHandler;
use Obcato\Core\DownloadForm;

class DownloadRequestHandler extends HttpRequestHandler {

    private DownloadDao $downloadDao;
    private ?Download $currentDownload;

    public function __construct() {
        $this->downloadDao = DownloadDaoMysql::getInstance();
    }

    public function handleGet(): void {
        $this->currentDownload = $this->getDownloadFromGetRequest();
    }

    private function getDownloadFromGetRequest(): ?Download {
        if (isset($_GET['download']) && $_GET['download'] != '') {
            return $this->downloadDao->getDownload($_GET['download']);
        }
        return null;
    }

    public function handlePost(): void {
        $this->currentDownload = $this->getDownloadFromPostRequest();
        if ($this->isAddDownloadAction()) {
            $this->addDownload();
        } else if ($this->isDeleteDownloadAction()) {
            $this->deleteDownload();
        } else if ($this->isUpdateDownloadAction()) {
            $this->updateDownload();
        }
    }

    private function getDownloadFromPostRequest() {
        if (isset($_POST['download_id']) && $_POST['download_id'] != '')
            return $this->downloadDao->getDownload($_POST['download_id']);
    }

    private function isAddDownloadAction(): bool {
        return isset($_POST["add_download_action"]) && $_POST["add_download_action"] != "";
    }

    private function addDownload(): void {
        $download = new Download();
        $download->setTitle("Nieuwe download");
        $this->downloadDao->persistDownload($download);
        $this->sendSuccessMessage("Download succesvol toegevoegd");
        $this->redirectTo($this->getBackendBaseUrl() . "&download=" . $download->getId());
    }

    private function isDeleteDownloadAction(): bool {
        return isset($_POST['action']) && $_POST['action'] == 'delete_download';
    }

    private function deleteDownload(): void {
        $this->deleteDownloadFile($this->currentDownload->getFilename());
        $this->downloadDao->deleteDownload($this->currentDownload->getId());
        $this->sendSuccessMessage('Download succesvol verwijderd');
        $this->redirectTo($this->getBackendBaseUrl());
    }

    private function deleteDownloadFile(string $filename): void {
        if (!$filename) return;
        $filepath = UPLOAD_DIR . '/' . $filename;
        if (file_exists($filepath)) {
            unlink($filepath);
        }
    }

    private function isUpdateDownloadAction(): bool {
        return isset($_POST['action']) && $_POST['action'] == 'update_download';
    }

    private function updateDownload(): void {
        try {
            $downloadForm = new DownloadForm($this->currentDownload);
            $downloadForm->loadFields();
            $this->saveUploadedFile($downloadForm);
            $this->downloadDao->updateDownload($this->currentDownload);
            $this->sendSuccessMessage("Download succesvol opgeslagen");
        } catch (FormException) {
            $this->sendErrorMessage("Download niet opgeslagen, verwerk de fouten");
        }
    }

    private function saveUploadedFile(DownloadForm $downloadForm): void {
        if ($downloadForm->getUploadPath()) {
            $newFilename = $this->getNewDownloadFilename($downloadForm->getUploadFileName());
            $this->deleteDownloadFile($this->currentDownload->getFilename());
            $this->moveDownloadToUploadDirectory($downloadForm->getUploadPath(), $newFilename);
        }
    }

    private function getNewDownloadFilename(string $downloadFilename): string {
        $currentDownloadId = $this->currentDownload->getId();
        return "UPLDWNL-00$currentDownloadId" . "_$downloadFilename";
    }

    private function moveDownloadToUploadDirectory(string $fromDir, string $newFilename): void {
        rename($fromDir, UPLOAD_DIR . '/' . $newFilename);
        $this->currentDownload->setFilename($newFilename);
    }

    public function getCurrentDownload(): ?Download {
        return $this->currentDownload;
    }

    public function isSearchAction(): bool {
        return isset($_GET['search_query']);
    }

    public function getSearchQuery(): ?string {
        if (isset($_GET['search_query'])) {
            return $_GET['search_query'];
        }
        return null;
    }
}