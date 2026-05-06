<?php

namespace Pageflow\Core\modules\images;

use Pageflow\Core\database\dao\FunctionalImageDao;
use Pageflow\Core\database\dao\FunctionalImageDaoMysql;
use Pageflow\Core\modules\images\model\FunctionalImage;
use Pageflow\Core\modules\images\model\FunctionalImageFolder;
use Pageflow\Core\request_handlers\HttpRequestHandler;
use Pageflow\Core\utilities\ImageUtility;
use const Pageflow\Core\UPLOAD_DIR;

class FunctionalImageRequestHandler extends HttpRequestHandler {

    private FunctionalImageDao $dao;
    private ?FunctionalImage $currentImage = null;
    private ?FunctionalImageFolder $currentFolder = null;

    public function __construct() {
        $this->dao = FunctionalImageDaoMysql::getInstance();
    }

    public function handleGet(): void {
        if (isset($_GET['fimg']) && $_GET['fimg'] !== '') {
            $this->currentImage = $this->dao->getFunctionalImage((int)$_GET['fimg']);
        }
        if (isset($_GET['fimg_folder']) && $_GET['fimg_folder'] !== '') {
            $this->currentFolder = $this->dao->getFunctionalImageFolder((int)$_GET['fimg_folder']);
        }
    }

    public function handlePost(): void {
        $action = $_POST['action'] ?? '';
        switch ($action) {
            case 'add_functional_image':
                $this->addImage();
                break;
            case 'update_functional_image':
                $this->currentImage = $this->dao->getFunctionalImage((int)($_POST['fimg_id'] ?? 0));
                $this->updateImage();
                break;
            case 'delete_functional_image':
                $this->currentImage = $this->dao->getFunctionalImage((int)($_POST['fimg_id'] ?? 0));
                $this->deleteImage();
                break;
            case 'add_functional_image_folder':
                $this->addFolder();
                break;
            case 'update_functional_image_folder':
                $this->currentFolder = $this->dao->getFunctionalImageFolder((int)($_POST['fimg_folder_id'] ?? 0));
                $this->updateFolder();
                break;
            case 'delete_functional_image_folder':
                $this->deleteFolder();
                break;
            case 'move_functional_image':
                $this->moveImage();
                break;
        }
    }

    public function getCurrentImage(): ?FunctionalImage {
        return $this->currentImage;
    }

    public function getCurrentFolder(): ?FunctionalImageFolder {
        return $this->currentFolder;
    }

    private function addImage(): void {
        $image = new FunctionalImage();
        $image->setTitle('Nieuwe functionele afbeelding');
        $image->setPublished(false);
        $folderId = isset($_POST['fimg_folder_id']) && $_POST['fimg_folder_id'] !== '' ? (int)$_POST['fimg_folder_id'] : null;
        $image->setFolderId($folderId);
        $this->dao->createFunctionalImage($image);
        $this->sendSuccessMessage($this->getTextResource('functional_images_save_success'));
        $this->redirectTo($this->getBackendBaseUrl() . '&fimg=' . $image->getId());
    }

    private function updateImage(): void {
        if (!$this->currentImage) return;
        $this->saveUploadedFile($this->currentImage);
        $this->currentImage->setTitle($_POST['fimg_title'] ?? '');
        $this->currentImage->setAltText($_POST['fimg_alt_text'] ?: null);
        $this->currentImage->setPublished(isset($_POST['fimg_published']));
        $this->dao->updateFunctionalImage($this->currentImage);
        $this->sendSuccessMessage($this->getTextResource('functional_images_save_success'));
    }

    private function deleteImage(): void {
        if (!$this->currentImage) return;
        $this->dao->deleteFunctionalImage($this->currentImage);
        $this->sendSuccessMessage($this->getTextResource('functional_images_delete_success'));
        $this->redirectTo($this->getBackendBaseUrl());
    }

    private function addFolder(): void {
        $folder = new FunctionalImageFolder();
        $folder->setName('Nieuwe map');
        $parentId = isset($_POST['fimg_parent_folder_id']) && $_POST['fimg_parent_folder_id'] !== '' ? (int)$_POST['fimg_parent_folder_id'] : null;
        $folder->setParentFolderId($parentId);
        $this->dao->createFolder($folder);
        $this->sendSuccessMessage($this->getTextResource('functional_images_folder_save_success'));
        $this->redirectTo($this->getBackendBaseUrl() . '&fimg_folder=' . $folder->getId());
    }

    private function updateFolder(): void {
        if (!$this->currentFolder) return;
        $this->currentFolder->setName($_POST['fimg_folder_name'] ?? '');
        $this->dao->updateFolder($this->currentFolder);
        $this->sendSuccessMessage($this->getTextResource('functional_images_folder_save_success'));
    }

    private function deleteFolder(): void {
        $id = (int)($_POST['fimg_folder_id'] ?? 0);
        if ($id) {
            $this->dao->deleteFolder($id);
        }
        $this->sendSuccessMessage($this->getTextResource('functional_images_folder_delete_success'));
        $this->redirectTo($this->getBackendBaseUrl());
    }

    private function moveImage(): void {
        $image = $this->dao->getFunctionalImage((int)($_POST['fimg_id'] ?? 0));
        if (!$image) return;
        $folderId = isset($_POST['fimg_folder_id']) && $_POST['fimg_folder_id'] !== '' ? (int)$_POST['fimg_folder_id'] : null;
        $image->setFolderId($folderId);
        $this->dao->updateFunctionalImage($image);
        $this->redirectTo($this->getBackendBaseUrl() . '&fimg=' . $image->getId());
    }

    private function saveUploadedFile(FunctionalImage $image): void {
        if (!isset($_FILES['fimg_file']) || !is_uploaded_file($_FILES['fimg_file']['tmp_name'])) {
            return;
        }
        $id = $image->getId();
        $originalName = pathinfo($_FILES['fimg_file']['name'], PATHINFO_FILENAME);
        $sanitized    = preg_replace('/[^A-Za-z0-9_\-]/', '_', $originalName);
        $ext          = strtolower(pathinfo($_FILES['fimg_file']['name'], PATHINFO_EXTENSION));
        $newFilename  = "FIMG-{$id}_{$sanitized}.{$ext}";

        ImageUtility::delete($image->getFilename());
        move_uploaded_file($_FILES['fimg_file']['tmp_name'], UPLOAD_DIR . '/' . $newFilename);
        $image->setFilename($newFilename);
    }
}
