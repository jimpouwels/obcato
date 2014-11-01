<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'database/dao/module_dao.php';
    require_once CMS_ROOT . 'database/dao/element_dao.php';

    class ComponentRequestHandler extends ModuleRequestHandler {

        private $_module_dao;
        private $_element_dao;
        private $_current_module;
        private $_current_element;

        public function __construct() {
            $this->_module_dao = ModuleDao::getInstance();
            $this->_element_dao = ElementDao::getInstance();
        }

        public function handleGet() {
            $this->_current_module = $this->getModuleFromGetRequest();
            $this->_current_element = $this->getElementFromGetRequest();
        }

        public function handlePost() {
        }

        public function getCurrentModule() {
            return $this->_current_module;
        }

        public function getCurrentElement() {
            return $this->_current_element;
        }

        private function getModuleFromGetRequest() {
            if (isset($_GET['module']) && $_GET['module'])
                return $this->_module_dao->getModule($_GET['module']);
        }

        private function getElementFromGetRequest() {
            if (isset($_GET['element']) && $_GET['element'])
                return $this->_element_dao->getElementType($_GET['element']);
        }
    }