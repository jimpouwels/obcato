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

        abstract function getIdentifier();
        abstract function getTitle();
        abstract function isPopup();
        abstract function getModuleGroup();
        abstract function getActivatorClassName();

        public function install() {
            $this->_logger->log('Installer voor component \'' . $this->getTitle() . '\' gestart');
            $this->installModule();
            $this->installStaticFiles(STATIC_DIR . '/modules/' . $this->getIdentifier());
            $this->installBackendTemplates(BACKEND_TEMPLATE_DIR . '/modules/' . $this->getIdentifier());
            $this->installModuleFiles();
        }

        public function unInstall() {
            $this->uninstallModule();
            $this->uninstallStaticFiles();
            $this->uninstallBackendTemplates();
            $this->uninstallModuleFiles();
            $this->runUninstallQueries();
        }

        private function installModule() {
            $module = new Module();
            $module->setTitle($this->getTitle());
            $module->setIdentifier($this->getIdentifier());
            $module->setIconUrl($this->getIconPath());
            $module->setModuleGroupId($this->_module_dao->getModuleGroupByTitle($this->getModuleGroup())->getId());
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

        private function installModuleFiles() {
            $target_dir = CMS_ROOT . 'modules/' . $this->getIdentifier();
            $this->createDir($target_dir);
            $this->_logger->log('Overige bestanden kopiëren naar ' . $target_dir);
            FileUtility::moveDirectoryContents(COMPONENT_TEMP_DIR, $target_dir);
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