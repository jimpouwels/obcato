<?php

namespace Pageflow\Core\modules\links;

use Pageflow\Core\core\model\Module;
use Pageflow\Core\modules\links\database\dao\ReusableLinkDao;
use Pageflow\Core\modules\links\database\dao\ReusableLinkDaoMysql;
use Pageflow\Core\modules\links\visuals\FolderEditor;
use Pageflow\Core\modules\links\visuals\LinkEditor;
use Pageflow\Core\view\views\ActionButtonAdd;
use Pageflow\Core\view\views\ActionButtonAddFolder;
use Pageflow\Core\view\views\ActionButtonDelete;
use Pageflow\Core\view\views\ActionButtonSave;
use Pageflow\Core\view\views\ModuleVisual;
use Pageflow\Core\view\views\TabMenu;

class LinksModuleVisual extends ModuleVisual {

    private LinksRequestHandler $requestHandler;
    private ReusableLinkDao $linkDao;

    public function __construct(Module $module) {
        parent::__construct($module);
        $this->requestHandler = new LinksRequestHandler();
        $this->linkDao = ReusableLinkDaoMysql::getInstance();
    }

    public function getTemplateFilename(): string {
        return "links/templates/root.tpl";
    }

    public function load(): void {
        $tree = $this->linkDao->getFolderTree();
        $this->assign('root_folders', $this->buildFolderData($tree['folders']));
        $this->assign('root_links',   $this->buildLinkData($tree['links']));

        $currentLink   = $this->requestHandler->getCurrentLink();
        $currentFolder = $this->requestHandler->getCurrentFolder();

        $this->assign('current_link_id',   $currentLink   ? $currentLink->getId()   : null);
        $this->assign('current_folder_id', $currentFolder ? $currentFolder->getId() : null);

        if ($currentLink) {
            $this->assign('editor_mode', 'link');
            $this->assign('editor', (new LinkEditor($currentLink))->render());
        } elseif ($currentFolder) {
            $this->assign('editor_mode', 'folder');
            $this->assign('editor', (new FolderEditor($currentFolder))->render());
        } else {
            $this->assign('editor_mode', 'none');
        }
    }

    public function getActionButtons(): array {
        $buttons       = [];
        $currentLink   = $this->requestHandler->getCurrentLink();
        $currentFolder = $this->requestHandler->getCurrentFolder();

        if ($currentLink) {
            $buttons[] = new ActionButtonSave('update_link');
            $buttons[] = new ActionButtonDelete('delete_link');
        } elseif ($currentFolder) {
            $buttons[] = new ActionButtonSave('update_folder');
            $buttons[] = new ActionButtonDelete('delete_folder');
        }

        $buttons[] = new ActionButtonAdd('add_link');
        $buttons[] = new ActionButtonAddFolder('add_folder');
        return $buttons;
    }

    public function renderStyles(): array {
        return [$this->getTemplateEngine()->fetch("links/templates/styles/module_links.css.tpl")];
    }

    public function renderScripts(): array {
        return [$this->getTemplateEngine()->fetch("links/templates/scripts/module_links.js.tpl")];
    }

    public function getRequestHandlers(): array {
        return [$this->requestHandler];
    }

    public function onRequestHandled(): void {
        // nothing
    }

    public function loadTabMenu(TabMenu $tabMenu): int {
        return $this->getCurrentTabId();
    }

    private function buildFolderData(array $folders): array {
        $data = [];
        foreach ($folders as $folder) {
            $data[] = [
                'id'          => $folder->getId(),
                'name'        => $folder->getName(),
                'sub_folders' => $this->buildFolderData($folder->getSubFolders()),
                'links'       => $this->buildLinkData($folder->getLinks()),
            ];
        }
        return $data;
    }

    private function buildLinkData(array $links): array {
        $data = [];
        foreach ($links as $link) {
            $data[] = [
                'id'        => $link->getId(),
                'title'     => $link->getTitle(),
                'url'       => $link->getUrl(),
                'folder_id' => $link->getFolderId(),
            ];
        }
        return $data;
    }

    private function buildAllFolderOptions(array $folders): array {
        $options = [['value' => '', 'name' => '— Geen map (root) —']];
        foreach ($folders as $folder) {
            $options[] = ['value' => $folder->getId(), 'name' => $folder->getName()];
        }
        return $options;
    }
}
