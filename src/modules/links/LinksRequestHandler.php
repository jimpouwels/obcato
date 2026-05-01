<?php

namespace Pageflow\Core\modules\links;

use Pageflow\Core\core\form\FormException;
use Pageflow\Core\modules\links\database\dao\ReusableLinkDao;
use Pageflow\Core\modules\links\database\dao\ReusableLinkDaoMysql;
use Pageflow\Core\modules\links\model\ReusableLink;
use Pageflow\Core\modules\links\model\ReusableLinkFolder;
use Pageflow\Core\request_handlers\HttpRequestHandler;
use Pageflow\Core\modules\links\LinksForm;

class LinksRequestHandler extends HttpRequestHandler {

    private ReusableLinkDao $linkDao;
    private ?ReusableLink $currentLink = null;
    private ?ReusableLinkFolder $currentFolder = null;

    public function __construct() {
        $this->linkDao = ReusableLinkDaoMysql::getInstance();
    }

    public function handleGet(): void {
        $this->currentLink = $this->getLinkFromGetRequest();
        $this->currentFolder = $this->getFolderFromGetRequest();
    }

    public function handlePost(): void {
        $action = $_POST['action'] ?? '';

        switch ($action) {
            case 'add_link':
                $this->addLink();
                break;
            case 'update_link':
                $this->updateLink();
                break;
            case 'delete_link':
                $this->deleteLink();
                break;
            case 'add_folder':
                $this->addFolder();
                break;
            case 'update_folder':
                $this->updateFolder();
                break;
            case 'delete_folder':
                $this->deleteFolder();
                break;
            case 'move_link':
                $this->moveLink();
                break;
        }
    }

    public function getCurrentLink(): ?ReusableLink {
        return $this->currentLink;
    }

    public function getCurrentFolder(): ?ReusableLinkFolder {
        return $this->currentFolder;
    }

    private function addLink(): void {
        $link = new ReusableLink();
        $link->setName('Nieuwe link');
        $link->setTitle('');
        $link->setUrl('');
        $folderId = isset($_POST['folder_id']) && $_POST['folder_id'] !== '' ? (int)$_POST['folder_id'] : null;
        $link->setFolderId($folderId);
        $this->linkDao->createLink($link);
        $this->sendSuccessMessage('Link toegevoegd');
        $this->redirectTo($this->getBackendBaseUrl() . '&link=' . $link->getId());
    }

    private function updateLink(): void {
         try {
            $this->currentLink = $this->getLinkFromPostRequest();
            if (!$this->currentLink) {
                $this->sendErrorMessage('Link niet gevonden');
                return;
            }
            $linkForm = new LinksForm($this->currentLink);
            $linkForm->loadFields();
            $this->linkDao->updateLink($this->currentLink);
            $this->sendSuccessMessage('Link opgeslagen');
         } catch (FormException $e) {
            $this->sendErrorMessage($this->getTextResource('link_not_saved_error_message'));
            return;
         }
    }

    private function deleteLink(): void {
        $linkId = $this->getLinkFromPostRequest()->getId();
        $this->linkDao->deleteLink($linkId);
        $this->sendSuccessMessage('Link verwijderd');
        $this->redirectTo($this->getBackendBaseUrl());
    }

    private function addFolder(): void {
        $folder = new ReusableLinkFolder();
        $folder->setName('Nieuwe map');
        $parentId = isset($_POST['parent_folder_id']) && $_POST['parent_folder_id'] !== '' ? (int)$_POST['parent_folder_id'] : null;
        $folder->setParentFolderId($parentId);
        $this->linkDao->createFolder($folder);
        $this->sendSuccessMessage('Map toegevoegd');
        $this->redirectTo($this->getBackendBaseUrl() . '&folder=' . $folder->getId());
    }

    private function updateFolder(): void {
        $folderId = $this->getFolderIdFromPostRequest();
        $folder = $this->linkDao->getFolder($folderId);
        if (!$folder) {
            $this->sendErrorMessage('Map niet gevonden');
            return;
        }
        $name = trim($_POST['name'] ?? '');
        if ($name === '') {
            $this->sendErrorMessage('Naam is verplicht');
            $this->currentFolder = $folder;
            return;
        }
        $folder->setName($name);
        $this->linkDao->updateFolder($folder);
        $this->currentFolder = $folder;
        $this->sendSuccessMessage('Map opgeslagen');
    }

    private function deleteFolder(): void {
        $folderId = $this->getFolderIdFromPostRequest();
        // TODO delete_mode not implemented
        if (($_POST['delete_mode'] ?? '') === 'unparent') {
            $links = $this->linkDao->getLinksByFolder($folderId);
            foreach ($links as $link) {
                $this->linkDao->moveLinkToFolder($link->getId(), null);
            }
        }
        $this->linkDao->deleteFolder($folderId);
        $this->sendSuccessMessage('Map verwijderd');
        $this->redirectTo($this->getBackendBaseUrl());
    }

    private function moveLink(): void {
        $linkId   = $this->getLinkIdFromPostRequest();
        $folderId = $this->getFolderIdFromPostRequest();
        $this->linkDao->moveLinkToFolder($linkId, $folderId);
        $this->sendSuccessMessage('Link verplaatst');
    }

    private function getLinkFromGetRequest(): ?ReusableLink {
        if (isset($_GET['link']) && $_GET['link'] !== '') {
            return $this->linkDao->getLink((int)$_GET['link']);
        }
        return null;
    }

    private function getFolderFromGetRequest(): ?ReusableLinkFolder {
        if (isset($_GET['folder']) && $_GET['folder'] !== '') {
            return $this->linkDao->getFolder((int)$_GET['folder']);
        }
        return null;
    }

    private function getLinkFromPostRequest(): ?ReusableLink {
        return $this->linkDao->getLink($this->getLinkIdFromPostRequest());
    }

    private function getLinkIdFromPostRequest(): ?int {
        if (isset($_POST['link_id']) && $_POST['link_id'] !== '') {
            return (int)$_POST['link_id'];
        }
        return null;
    }

    private function getFolderIdFromPostRequest(): ?int {
        if (isset($_POST['folder_id']) && $_POST['folder_id'] !== '') {
            return (int)$_POST['folder_id'];
        }
        return null;
    }
}
