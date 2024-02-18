<?php
require_once CMS_ROOT . "/view/views/ModuleVisual.php";
require_once CMS_ROOT . "/modules/authorization/AuthorizationRequestHandler.php";
require_once CMS_ROOT . "/modules/authorization/visuals/UserList.php";
require_once CMS_ROOT . "/modules/authorization/visuals/UserEditor.php";

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

    public function getTabMenu(): ?TabMenu {
        return null;
    }

}