<?php

namespace Pageflow\Core\modules\authorization;

use Pageflow\Core\core\model\Module;
use Pageflow\Core\modules\authorization\model\User;
use Pageflow\Core\modules\authorization\visuals\UserEditor;
use Pageflow\Core\modules\authorization\visuals\UserList;
use Pageflow\Core\view\views\ActionButtonAdd;
use Pageflow\Core\view\views\ActionButtonDelete;
use Pageflow\Core\view\views\ActionButtonSave;
use Pageflow\Core\view\views\ModuleVisual;
use Pageflow\Core\view\views\TabMenu;

class AuthorizationModuleVisual extends ModuleVisual {

    private static string $HEAD_INCLUDES_TEMPLATE = "authorization/templates/head_includes.tpl";
    private ?User $currentUser;
    private AuthorizationRequestHandler $authorizationRequestHandler;

    public function __construct(Module $module) {
        parent::__construct($module);
        $this->authorizationRequestHandler = new AuthorizationRequestHandler();
    }

    public function getTemplateFilename(): string {
        return "authorization/templates/root.tpl";
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

    public function renderStyles(): array {
        $styles = array();
        $styles[] = $this->getTemplateEngine()->fetch("authorization/templates/styles/module_authorization.css.tpl");
        return $styles;
    }

    public function renderScripts(): array {
        $scripts = array();
        $scripts[] = $this->getTemplateEngine()->fetch("authorization/templates/scripts/module_authorization.js.tpl");
        return $scripts;
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