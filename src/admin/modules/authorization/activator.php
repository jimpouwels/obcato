<?php

namespace Obcato\Core\admin\modules\authorization;

use Obcato\Core\admin\core\model\Module;
use Obcato\Core\admin\modules\authorization\model\User;
use Obcato\Core\admin\modules\authorization\visuals\UserEditor;
use Obcato\Core\admin\modules\authorization\visuals\UserList;
use Obcato\Core\admin\view\views\ActionButtonAdd;
use Obcato\Core\admin\view\views\ActionButtonDelete;
use Obcato\Core\admin\view\views\ActionButtonSave;
use Obcato\Core\admin\view\views\ModuleVisual;
use Obcato\Core\admin\view\views\TabMenu;

class AuthorizationModuleVisual extends ModuleVisual {

    private static string $HEAD_INCLUDES_TEMPLATE = "modules/authorization/head_includes.tpl";
    private ?User $currentUser;
    private AuthorizationRequestHandler $authorizationRequestHandler;

    public function __construct(Module $module) {
        parent::__construct($module);
        $this->authorizationRequestHandler = new AuthorizationRequestHandler();
    }

    public function getTemplateFilename(): string {
        return "modules/authorization/root.tpl";
    }

    public function load(): void {
        $userList = new UserList($this->currentUser);
        $userEditor = new UserEditor($this->currentUser);
        $this->assign("user_list", $userList->render());
        $this->assign("user_editor", $userEditor->render());
    }

    public function getActionButtons(): array {
        $actionButtons = array();
        if (!is_null($this->currentUser)) {
            $actionButtons[] = new ActionButtonSave('update_user');
            if (!$this->currentUser->isLoggedInUser()) {
                $actionButtons[] = new ActionButtonDelete('delete_user');
            }
        }
        $actionButtons[] = new ActionButtonAdd('add_user');
        return $actionButtons;
    }

    public function renderHeadIncludes(): string {
        return $this->getTemplateEngine()->fetch(self::$HEAD_INCLUDES_TEMPLATE);
    }

    public function getRequestHandlers(): array {
        $requestHandlers = array();
        $requestHandlers[] = $this->authorizationRequestHandler;
        return $requestHandlers;
    }

    public function onRequestHandled(): void {
        $this->currentUser = $this->authorizationRequestHandler->getCurrentUser();
    }

    public function loadTabMenu(TabMenu $tabMenu): int {
        return $this->getCurrentTabId();
    }

}