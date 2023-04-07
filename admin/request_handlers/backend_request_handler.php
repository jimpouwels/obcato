<?php
    
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "request_handlers/http_request_handler.php";
    require_once CMS_ROOT . "core/blackboard.php";
    
    class BackendRequestHandler extends HttpRequestHandler {
            
        private $_module_dao;
        private $_callback;
        
        public function __construct($callback) {
            $this->_callback = $callback;
            $this->_module_dao = ModuleDao::getInstance();
        }
    
        public function handleGet() {
            $this->setCurrentModule();
        }

        public function handlePost() {
            $this->setCurrentModule();
        }

        public function setCurrentModule() {
            $current_module = null;
            $module_id = $this->getParam('module_id');
            if ($module_id) {
                $current_module = $this->_module_dao->getModule($module_id);
                BlackBoard::$MODULE_ID = $module_id;
                $module_tab_id = $this->getParam('module_tab_id');
                if (!is_null($module_tab_id)) {
                    BlackBoard::$MODULE_TAB_ID = $module_tab_id;
                }
            }
            $this->_callback->setCurrentModule($current_module);
        }

        private function getParam($name) {
            $value = null;
            if (isset($_GET[$name])) {
                $value = $_GET[$name];
            } else if (isset($_POST[$name])) {
                $value = $_POST[$name];
            }
            return $value;
        }
        
    }

?>