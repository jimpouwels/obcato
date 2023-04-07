<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "database/dao/authorization_dao.php";

    class UserList extends Panel {

        private static $USER_LIST_TEMPLATE = "modules/authorization/user_list.tpl";
        private $_template_engine;
        private $_authorization_dao;
        private $_current_user;

        public function __construct($current_user) {
            parent::__construct('Gebruikers', 'user_tree_fieldset');
            $this->_current_user = $current_user;
            $this->_authorization_dao = AuthorizationDao::getInstance();
            $this->_template_engine = TemplateEngine::getInstance();
        }

        public function renderVisual(): string {
            return parent::renderVisual();
        }

        public function renderPanelContent() {
            $this->_template_engine->assign("users", $this->getAllUsers());
            return $this->_template_engine->fetch(self::$USER_LIST_TEMPLATE);
        }

        public function getAllUsers() {
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
