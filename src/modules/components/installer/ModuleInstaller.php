<?php

namespace Obcato\Core\modules\components\installer;

use Obcato\Core\core\model\Module;
use Obcato\Core\database\dao\ModuleDao;
use Obcato\Core\database\dao\ModuleDaoMysql;
use Obcato\Core\utilities\FileUtility;
use const Obcato\CMS_ROOT;
use const Obcato\BACKEND_TEMPLATE_DIR;
use const Obcato\core\STATIC_DIR;

abstract class ModuleInstaller extends Installer {

    public static string $CUSTOM_INSTALLER_CLASSNAME = 'CustomModuleInstaller';
    private Logger $logger;
    private ModuleDao $moduleDao;

    public function __construct($logger) {
        parent::__construct($logger);
        $this->logger = $logger;
        $this->moduleDao = ModuleDaoMysql::getInstance();
    }

    public abstract function isPopup(): bool;

    public abstract function getModuleGroup(): string;

    public abstract function getActivatorClassName(): string;

    public function install(): void {
        $this->logger->log('Installer voor component \'' . $this->getIdentifier() . '\' gestart');
        $this->installModule();
        $this->installStaticFiles(STATIC_DIR . '/modules/' . $this->getIdentifier());
        $this->installTextResources(STATIC_DIR . '/text_resources');
        $this->installBackendTemplates(BACKEND_TEMPLATE_DIR . '/modules/' . $this->getIdentifier());
        $this->installComponentFiles(CMS_ROOT . 'modules/' . $this->getIdentifier());
    }

    public function unInstall(): void {
        $this->uninstallModule();
        $this->uninstallStaticFiles();
        $this->uninstallTextResources();
        $this->uninstallBackendTemplates();
        $this->uninstallModuleFiles();
        $this->runUninstallQueries();
    }

    private function installModule(): void {
        $module = new Module();
        $module->setIdentifier($this->getIdentifier());
        $module->setModuleGroupId($this->moduleDao->getModuleGroupByIdentifier($this->getModuleGroup())->getId());
        $module->setPopUp($this->isPopup());
        $module->setEnabled(true);
        $module->setClass($this->getActivatorClassName());
        if (!$this->moduleDao->getModuleByIdentifier($module->getIdentifier())) {
            $this->runInstallQueries();
            $this->logger->log('Module wordt toegevoegd aan de database');
            $this->moduleDao->persistModule($module);
        } else {
            $this->logger->log('Module database record wordt geupdate');
            $this->moduleDao->updateModule($module);
        }
    }

    private function uninstallModule(): void {
        $this->moduleDao->removeModule($this->getIdentifier());
    }

    private function uninstallModuleFiles(): void {
        FileUtility::recursiveDelete(CMS_ROOT . 'modules/' . $this->getIdentifier(), true);
    }

    private function uninstallStaticFiles(): void {
        FileUtility::recursiveDelete(STATIC_DIR . '/modules/' . $this->getIdentifier(), true);
    }

    private function uninstallBackendTemplates(): void {
        FileUtility::recursiveDelete(BACKEND_TEMPLATE_DIR . '/modules/' . $this->getIdentifier(), true);
    }

}