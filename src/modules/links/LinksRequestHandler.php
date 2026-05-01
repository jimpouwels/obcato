<?php

namespace Pageflow\Core\modules\links;

use Pageflow\Core\core\form\FormException;
use Pageflow\Core\modules\links\database\dao\ReusableLinkDao;
use Pageflow\Core\modules\links\database\dao\ReusableLinkDaoMysql;
use Pageflow\Core\modules\links\model\ReusableLink;
use Pageflow\Core\modules\links\model\ReusableLinkFolder;
use Pageflow\Core\request_handlers\HttpRequestHandler;

class LinksRequestHandler extends HttpRequestHandler {

    private ReusableLinkDao $linkDao;
    private ?ReusableLink $currentLink = null;
    private ?ReusableLinkFolder $currentFolder = null;

    public function __construct() {
        $this->linkDao = ReusableLinkDaoMysql::getInstance();
    }

    public function handleGet(): void {
        if (isset($_GET['link']) && $_GET['link'] !== '') {
            $this->currentLink = $this->linkDao->getLink((int)$_GET['link']);
        } elseif (isset($_GET['folder']) && $_GET['folder'] !== '') {
            $this->currentFolder = $this->linkDao->getFolder((int)$_GET['folder']);
        }
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
        $link->setTitle('Nieuwe link');
        $link->setUrl('');
        $folderId = isset($_POST['folder_id']) && $_POST['folder_id'] !== '' ? (int)$_POST['folder_id'] : null;
        $link->setFolderId($folderId);
        $this->linkDao->createLink($link);
        $this->sendSuccessMessage('Link toegevoegd');
        $this->redirectTo($this->getBackendBaseUrl() . '&link=' . $link->getId());
    }

    private function updateLink(): void {
        $linkId = (int)($_POST['link_id'] ?? 0);
        $link = $this->linkDao->getLink($linkId);
        if (!$link) {
            $this->sendErrorMessage('Link niet gevonden');
            return;
        }
        $title = trim($_POST['title'] ?? '');
        $url = trim($_POST['url'] ?? '');
        if ($title === '') {
            $this->sendErrorMessage('Titel is verplicht');
            $this->currentLink = $link;
            return;
        }
        $link->setTitle($title);
        $link->setUrl($url);
        $folderId = isset($_POST['folder_id']) && $_POST['folder_id'] !== '' ? (int)$_POST['folder_id'] : $link->getFolderId();
        $link->setFolderId($folderId);
        $this->linkDao->updateLink($link);
        $this->currentLink = $link;
        $this->sendSuccessMessage('Link opgeslagen');
    }

    private function deleteLink(): void {
        $linkId = (int)($_POST['link_id'] ?? 0);
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
        $folderId = (int)($_POST['folder_id'] ?? 0);
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
        $folderId = (int)($_POST['folder_id'] ?? 0);
        // Unparent links if requested, otherwise cascade deletes them via DB FK
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
        $linkId   = (int)($_POST['link_id'] ?? 0);
        $folderId = isset($_POST['folder_id']) && $_POST['folder_id'] !== '' ? (int)$_POST['folder_id'] : null;
        $this->linkDao->moveLinkToFolder($linkId, $folderId);
        $this->sendSuccessMessage('Link verplaatst');
    }
}
