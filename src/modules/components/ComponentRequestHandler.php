<?php

namespace Obcato\Core\modules\components;

use Obcato\Core\core\model\ElementType;
use Obcato\Core\core\model\Module;
use Obcato\Core\database\dao\ElementDao;
use Obcato\Core\database\dao\ElementDaoMysql;
use Obcato\Core\database\dao\ModuleDao;
use Obcato\Core\database\dao\ModuleDaoMysql;
use Obcato\Core\modules\components\installer\Logger;
use Obcato\Core\request_handlers\HttpRequestHandler;

class ComponentRequestHandler extends HttpRequestHandler {

    private ModuleDao $moduleDao;
    private ElementDao $elementDao;
    private ?Module $currentModule = null;
    private ?ElementType $currentElementType = null;

    public function __construct() {
        $this->moduleDao = ModuleDaoMysql::getInstance();
        $this->elementDao = ElementDaoMysql::getInstance();
    }

    public function handleGet(): void {
        $this->currentModule = $this->getModuleFromGetRequest();
        $this->currentElementType = $this->getElementTypeFromGetRequest();
    }

    private function getModuleFromGetRequest() {
        if (isset($_GET['module']) && $_GET['module'])
            return $this->moduleDao->getModule($_GET['module']);
    }

    private function getElementTypeFromGetRequest(): ?ElementType {
        if (isset($_GET['element']) && $_GET['element']) {
            return $this->elementDao->getElementType($_GET['element']);
        }
        return null;
    }

    public function handlePost(): void {
        if ($this->isUninstallAction()) {
            $this->uninstallComponent();
        }
    }

    public function getCurrentModule(): ?Module {
        return $this->currentModule;
    }

    public function getCurrentElementType(): ?ElementType {
        return $this->currentElementType;
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
        return $this->moduleDao->getModule($_POST['module_id']);
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
        return $this->elementDao->getElementType($_POST['element_id']);
    }
}