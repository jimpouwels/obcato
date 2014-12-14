<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'utilities/file_utility.php';
    require_once CMS_ROOT . 'database/dao/module_dao.php';
    require_once CMS_ROOT . 'core/data/module.php';
    require_once CMS_ROOT . 'modules/components/installer/installer.php';

    abstract class ModuleInstaller extends Installer {

        public static $CUSTOM_INSTALLER_CLASSNAME = 'CustomModuleInstaller';
        private $_logger;
        private $_module_dao;

        public function __construct($logger) {
            parent::__construct($logger);
            $this->_logger = $logger;
            $this->_module_dao = ModuleDao::getInstance();
        }

        abstract function getTitleTextResourceIdentifier();
        abstract function isPopup();
        abstract function getModuleGroup();
        abstract function getActivatorClassName();

        public function install() {
            $this->_logger->log('Installer voor component \'' . $this->getIdentifier() . '\' gestart');
            $this->installModule();
            $this->installStaticFiles(STATIC_DIR . '/modules/' . $this->getIdentifier());
            $this->installTextResources(STATIC_DIR . '/text_resources');
            $this->installBackendTemplates(BACKEND_TEMPLATE_DIR . '/modules/' . $this->getIdentifier());
            $this->installComponentFiles(CMS_ROOT . 'modules/' . $this->getIdentifier());
        }

        public function unInstall() {
            $this->uninstallModule();
            $this->uninstallStaticFiles();
            $this->uninstallTextResources();
            $this->uninstallBackendTemplates();
            $this->uninstallModuleFiles();
            $this->runUninstallQueries();
        }

        private function installModule() {
            $module = new Module();
            $module->setTitleTextResourceIdentifier($this->getTitleTextResourceIdentifier());
            $module->setIdentifier($this->getIdentifier());
            $module->setIconUrl($this->getIconPath());
            $module->setModuleGroupId($this->_module_dao->getModuleGroupByIdentifier($this->getModuleGroup())->getId());
            $module->setPopUp($this->isPopup());
            $module->setEnabled(true);
            $module->setClass($this->getActivatorClassName());
            if (!$this->_module_dao->getModuleByIdentifier($module->getIdentifier())) {
                $this->runInstallQueries();
                $this->_logger->log('Module wordt toegevoegd aan de database');
                $this->_module_dao->persistModule($module);
            } else {
                $this->_logger->log('Module database record wordt geupdate');
                $this->_module_dao->updateModule($module);
            }
        }

        private function uninstallModule() {
            $this->_module_dao->removeModule($this->getIdentifier());
        }

        private function uninstallModuleFiles() {
            FileUtility::recursiveDelete(CMS_ROOT . 'modules/' . $this->getIdentifier(), true);
        }

        private function uninstallStaticFiles() {
            FileUtility::recursiveDelete(STATIC_DIR . '/modules/' . $this->getIdentifier(), true);
        }

        private function uninstallBackendTemplates() {
            FileUtility::recursiveDelete(BACKEND_TEMPLATE_DIR . '/modules/' . $this->getIdentifier(), true);
        }

    }