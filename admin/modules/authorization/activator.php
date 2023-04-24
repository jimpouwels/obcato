<?php
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/module_visual.php";
    require_once CMS_ROOT . "modules/authorization/authorization_request_handler.php";
    require_once CMS_ROOT . "modules/authorization/visuals/user_list.php";
    require_once CMS_ROOT . "modules/authorization/visuals/user_editor.php";

    class AuthorizationModuleVisual extends ModuleVisual {
    
        private static string $HEAD_INCLUDES_TEMPLATE = "modules/authorization/head_includes.tpl";
        private ?User $_current_user;
        private AuthorizationRequestHandler $_authorization_request_handler;
        
        public function __construct(Module $authorization_module) {
            parent::__construct($authorization_module);
            $this->_authorization_request_handler = new AuthorizationRequestHandler();
        }
    
        public function getTemplateFilename(): string {
            return  "modules/authorization/root.tpl";
        }

        public function load(): void {
            $user_list = new UserList($this->_current_user);
            $user_editor = new UserEditor($this->_current_user);
            $this->assign("user_list", $user_list->render());
            $this->assign("user_editor", $user_editor->render());
        }
    
        public function getActionButtons(): array {
            $action_buttons = array();
            if (!is_null($this->_current_user)) {
                $action_buttons[] = new ActionButtonSave('update_user');
                if (!$this->_current_user->isLoggedInUser()) {
                    $action_buttons[] = new ActionButtonDelete('delete_user');
                }
            }
            $action_buttons[] = new ActionButtonAdd('add_user');
            return $action_buttons;
        }
        
        public function renderHeadIncludes(): string {
            return $this->getTemplateEngine()->fetch(self::$HEAD_INCLUDES_TEMPLATE);
        }
        
        public function getRequestHandlers(): array {
            $request_handlers = array();
            $request_handlers[] = $this->_authorization_request_handler;
            return $request_handlers;
        }
        
        public function onRequestHandled(): void {
            $this->_current_user = $this->_authorization_request_handler->getCurrentUser();
        }
    
    }
    
?>