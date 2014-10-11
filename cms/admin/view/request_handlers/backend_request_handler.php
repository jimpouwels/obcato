<?php
	
	defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/request_handlers/http_request_handler.php";
	
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

        public function setCurrentModule()
        {
            $current_module = null;
            if (isset($_GET['home'])) {
                unset($_SESSION['module_id']);
            } else if (isset($_GET['module_id'])) {
                $_SESSION['module_id'] = $_GET['module_id'];
                unset($_SESSION['module_tab']);
            }

            if (isset($_SESSION['module_id']))
                $current_module = $this->_module_dao->getModule($_SESSION['module_id']);
            $this->_callback->setCurrentModule($current_module);
        }
		
	}

?>