<?php

namespace Obcato\Core\admin\modules\authorization;

use Obcato\ComponentApi\TabMenu;
use Obcato\ComponentApi\TemplateEngine;
use Obcato\Core\admin\modules\authorization\model\User;
use Obcato\Core\admin\modules\authorization\visuals\UserEditor;
use Obcato\Core\admin\modules\authorization\visuals\UserList;
use Obcato\Core\admin\view\views\ActionButtonAdd;
use Obcato\Core\admin\view\views\ActionButtonDelete;
use Obcato\Core\admin\view\views\ActionButtonSave;
use Obcato\Core\admin\view\views\ModuleVisual;

class AuthorizationModuleVisual extends ModuleVisual {

    private static string $HEAD_INCLUDES_TEMPLATE = "modules/authorization/head_includes.tpl";
    private ?User $currentUser;
    private AuthorizationRequestHandler $authorizationRequestHandler;

    public function __construct(TemplateEngine $templateEngine) {
        parent::__construct($templateEngine);
        $this->authorizationRequestHandler = new AuthorizationRequestHandler();
    }

    public function getTemplateFilename(): string {
        return "modules/authorization/root.tpl";
    }

    public function load(): void {
        $userList = new UserList($this->getTemplateEngine(), $this->currentUser);
        $userEditor = new UserEditor($this->getTemplateEngine(), $this->currentUser);
        $this->assign("user_list", $userList->render());
        $this->assign("user_editor", $userEditor->render());
    }

    public function getActionButtons(): array {
        $actionButtons = array();
        if (!is_null($this->currentUser)) {
            $actionButtons[] = new ActionButtonSave($this->getTemplateEngine(), 'update_user');
            if (!$this->currentUser->isLoggedInUser()) {
                $actionButtons[] = new ActionButtonDelete($this->getTemplateEngine(), 'delete_user');
            }
        }
        $actionButtons[] = new ActionButtonAdd($this->getTemplateEngine(), 'add_user');
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