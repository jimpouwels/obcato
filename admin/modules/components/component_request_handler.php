<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'database/dao/module_dao.php';
    require_once CMS_ROOT . 'database/dao/element_dao.php';
    require_once CMS_ROOT . 'modules/components/installer/logger.php';

    class ComponentRequestHandler extends HttpRequestHandler {

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
            if ($this->isUninstallAction())
                $this->uninstallComponent();
        }

        public function getCurrentModule() {
            return $this->_current_module;
        }

        public function getCurrentElement() {
            return $this->_current_element;
        }

        private function uninstallComponent() {
            if ($this->isUninstallModuleAction())
                $this->uninstallModule();
            else if ($this->isUninstalElementAction())
                $this->uninstallElement();
        }

        private function uninstallModule() {
            $module = $this->getModuleFromPostRequest();
            include_once CMS_ROOT . '/modules/' . $module->getIdentifier() . '/installer.php';
            $installer = new CustomModuleInstaller(new Logger());
            $installer->uninstall();
            $this->sendSuccessMessage('Component succesvol verwijderd');
        }

        private function uninstallElement() {
            $element = $this->getElementFromPostRequest();
            include_once CMS_ROOT . '/elements/' . $element->getIdentifier() . '/installer.php';
            $installer = new CustomElementInstaller(new Logger());
            $installer->uninstall();
            $this->sendSuccessMessage('Component succesvol verwijderd');
        }

        private function getModuleFromPostRequest() {
            return $this->_module_dao->getModule($_POST['module_id']);
        }

        private function getElementFromPostRequest() {
            return $this->_element_dao->getElementType($_POST['element_id']);
        }

        private function getModuleFromGetRequest() {
            if (isset($_GET['module']) && $_GET['module'])
                return $this->_module_dao->getModule($_GET['module']);
        }

        private function getElementFromGetRequest() {
            if (isset($_GET['element']) && $_GET['element'])
                return $this->_element_dao->getElementType($_GET['element']);
        }

        private function isUninstallAction() {
            return isset($_POST['action']) && $_POST['action'] == 'uninstall_component';
        }

        private function isUninstallModuleAction() {
            return isset($_POST['module_id']) && $_POST['module_id'];
        }

        private function isUninstalElementAction() {
            return isset($_POST['element_id']) && $_POST['element_id'];
        }
    }