<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "database/dao/authorization_dao.php";

    class UserList extends Panel {

        private static string $USER_LIST_TEMPLATE = "modules/authorization/user_list.tpl";
        private AuthorizationDao $_authorization_dao;
        private ?User $_current_user = null;

        public function __construct(?User $current_user) {
            parent::__construct('Gebruikers', 'user_tree_fieldset');
            $this->_current_user = $current_user;
            $this->_authorization_dao = AuthorizationDao::getInstance();
        }

        public function render(): string {
            return parent::render();
        }

        public function renderPanelContent(): string {
            $this->getTemplateEngine()->assign("users", $this->getAllUsers());
            return $this->getTemplateEngine()->fetch(self::$USER_LIST_TEMPLATE);
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
