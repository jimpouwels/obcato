<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'database/dao/module_dao.php';

    class ComponentRequestHandler extends ModuleRequestHandler {

        private $_module_dao;
        private $_current_module;

        public function __construct() {
            $this->_module_dao = ModuleDao::getInstance();
        }

        public function handleGet() {
            $this->_current_module = $this->getModuleFromGetRequest();
        }

        public function handlePost() {
        }

        public function getCurrentModule() {
            return $this->_current_module;
        }

        private function getModuleFromGetRequest() {
            if (isset($_GET['module']) && $_GET['module'])
                return $this->_module_dao->getModule($_GET['module']);
        }
    }