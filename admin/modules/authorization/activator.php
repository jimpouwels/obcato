<?php
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/module_visual.php";
    require_once CMS_ROOT . "modules/authorization/authorization_pre_handler.php";
    require_once CMS_ROOT . "modules/authorization/visuals/user_list.php";
    require_once CMS_ROOT . "modules/authorization/visuals/user_editor.php";

    class AuthorizationModuleVisual extends ModuleVisual {
    
        private static $AUTHORIZATION_MODULE_TEMPLATE = "modules/authorization/root.tpl";
        private static $HEAD_INCLUDES_TEMPLATE = "modules/authorization/head_includes.tpl";
        private $_current_user;
        private $_authorization_pre_handler;
        private $_authorization_module;
        
        public function __construct($authorization_module) {
            parent::__construct($authorization_module);
            $this->_authorization_pre_handler = new AuthorizationPreHandler();
            $this->_authorization_module = $authorization_module;
        }
    
        public function render(): string {
            $user_list = new UserList($this->_current_user);
            $user_editor = new UserEditor($this->_current_user);
            $this->getTemplateEngine()->assign("user_list", $user_list->render());
            $this->getTemplateEngine()->assign("user_editor", $user_editor->render());
            return $this->getTemplateEngine()->fetch(self::$AUTHORIZATION_MODULE_TEMPLATE);
        }
    
        public function getActionButtons() {
            $action_buttons = array();
            if (!is_null($this->_current_user)) {
                $action_buttons[] = new ActionButtonSave('update_user');
                if (!$this->_current_user->isLoggedInUser())
                    $action_buttons[] = new ActionButtonDelete('delete_user');
            }
            $action_buttons[] = new ActionButtonAdd('add_user');
            return $action_buttons;
        }
        
        public function getHeadIncludes() {
            return $this->getTemplateEngine()->fetch(self::$HEAD_INCLUDES_TEMPLATE);
        }
        
        public function getRequestHandlers() {
            $pre_handlers = array();
            $pre_handlers[] = $this->_authorization_pre_handler;
            return $pre_handlers;
        }
        
        public function onPreHandled() {
            $this->_current_user = $this->_authorization_pre_handler->getCurrentUser();
        }
    
    }
    
?>