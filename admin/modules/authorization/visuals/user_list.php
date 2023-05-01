<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "database/dao/authorization_dao.php";

    class UserList extends Panel {

        private static string $USER_LIST_TEMPLATE = "modules/authorization/user_list.tpl";
        private AuthorizationDao $_authorization_dao;
        private ?User $_current_user = null;

        public function __construct(?User $current_user) {
            parent::__construct('users_list_panel_title', 'user_tree_fieldset');
            $this->_current_user = $current_user;
            $this->_authorization_dao = AuthorizationDao::getInstance();
        }

        public function getPanelContentTemplate(): string {
            return "modules/authorization/user_list.tpl";
        }

        public function loadPanelContent(Smarty_Internal_Data $data): void {
            $data->assign("users", $this->getAllUsers());
        }

        public function getAllUsers(): array {
            $users = array();
            foreach ($this->_authorization_dao->getAllUsers() as $user) {
                $user_values = array();
                $user_values["id"] = $user->getId();
                $user_values["fullname"] = $user->getFullName();
                $user_values["is_current"] = $user->getId() == $this->_current_user->getId();
                $users[] = $user_values;
            }
            return $users;
        }
    }
