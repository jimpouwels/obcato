<?php

namespace Obcato\Core\modules\authorization\visuals;

use Obcato\Core\database\dao\AuthorizationDao;
use Obcato\Core\database\dao\AuthorizationDaoMysql;
use Obcato\Core\modules\authorization\model\User;
use Obcato\Core\view\TemplateData;
use Obcato\Core\view\views\Panel;

class UserList extends Panel {

    private AuthorizationDao $authorizationDao;
    private ?User $currentUser;

    public function __construct(?User $currentUser) {
        parent::__construct('users_list_panel_title', 'user_tree_fieldset');
        $this->currentUser = $currentUser;
        $this->authorizationDao = AuthorizationDaoMysql::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return "authorization/templates/user_list.tpl";
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
