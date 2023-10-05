<?php
require_once CMS_ROOT . '/database/dao/ModuleDaoMysql.php';
require_once CMS_ROOT . '/database/dao/ElementDaoMysql.php';
require_once CMS_ROOT . '/modules/components/installer/Logger.php';

class ComponentRequestHandler extends HttpRequestHandler {

    private $_module_dao;
    private $_element_dao;
    private $_current_module;
    private $_current_element;

    public function __construct() {
        $this->_module_dao = ModuleDaoMysql::getInstance();
        $this->_element_dao = ElementDaoMysql::getInstance();
    }

    public function handleGet(): void {
        $this->_current_module = $this->getModuleFromGetRequest();
        $this->_current_element = $this->getElementFromGetRequest();
    }

    private function getModuleFromGetRequest() {
        if (isset($_GET['module']) && $_GET['module'])
            return $this->_module_dao->getModule($_GET['module']);
    }

    private function getElementFromGetRequest() {
        if (isset($_GET['element']) && $_GET['element'])
            return $this->_element_dao->getElementType($_GET['element']);
    }

    public function handlePost(): void {
        if ($this->isUninstallAction())
            $this->uninstallComponent();
    }

    private function isUninstallAction(): bool {
        return isset($_POST['action']) && $_POST['action'] == 'uninstall_component';
    }

    private function uninstallComponent(): void {
        if ($this->isUninstallModuleAction()) {
            $this->uninstallModule();
        } else if ($this->isUninstalElementAction()) {
            $this->uninstallElement();
        }
    }

    private function isUninstallModuleAction(): bool {
        return isset($_POST['module_id']) && $_POST['module_id'];
    }

    private function uninstallModule(): void {
        $module = $this->getModuleFromPostRequest();
        require_once CMS_ROOT . '/modules/' . $module->getIdentifier() . '/installer.php';
        $installer = new CustomModuleInstaller(new Logger());
        $installer->uninstall();
        $this->sendSuccessMessage('Component succesvol verwijderd');
    }

    private function getModuleFromPostRequest(): Module {
        return $this->_module_dao->getModule($_POST['module_id']);
    }

    private function isUninstalElementAction(): bool {
        return isset($_POST['element_id']) && $_POST['element_id'];
    }

    private function uninstallElement(): void {
        $element = $this->getElementFromPostRequest();
        require_once CMS_ROOT . '/elements/' . $element->getIdentifier() . '/installer.php';
        $installer = new CustomElementInstaller(new Logger());
        $installer->uninstall();
        $this->sendSuccessMessage('Component succesvol verwijderd');
    }

    private function getElementFromPostRequest(): ElementType {
        return $this->_element_dao->getElementType($_POST['element_id']);
    }

    public function getCurrentModule(): Module {
        return $this->_current_module;
    }

    public function getCurrentElement(): Element {
        return $this->_current_element;
    }
}