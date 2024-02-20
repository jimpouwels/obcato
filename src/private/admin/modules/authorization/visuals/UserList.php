<?php

namespace Obcato\Core;

use Obcato\ComponentApi\TemplateData;
use Obcato\ComponentApi\TemplateEngine;

class UserList extends Panel {

    private AuthorizationDao $authorizationDao;
    private ?User $currentUser;

    public function __construct(TemplateEngine $templateEngine, ?User $currentUser) {
        parent::__construct($templateEngine, 'users_list_panel_title', 'user_tree_fieldset');
        $this->currentUser = $currentUser;
        $this->authorizationDao = AuthorizationDaoMysql::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return "modules/authorization/user_list.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $data->assign("users", $this->getAllUsers());
    }

    public function getAllUsers(): array {
        $users = array();
        foreach ($this->authorizationDao->getAllUsers() as $user) {
            $user_values = array();
            $user_values["id"] = $user->getId();
            $user_values["fullname"] = $user->getFullName();
            $user_values["is_current"] = $user->getId() == $this->currentUser->getId();
            $users[] = $user_values;
        }
        return $users;
    }
}
