<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'utilities/file_utility.php';
    require_once CMS_ROOT . 'database/dao/module_dao.php';
    require_once CMS_ROOT . 'core/data/module.php';
    require_once CMS_ROOT . 'database/mysql_connector.php';

    abstract class ComponentInstaller {

        public static $CUSTOM_INSTALLER_CLASSNAME = 'CustomModuleInstaller';
        private $_logger;
        private $_module_dao;
        private $_mysql_connector;

        public function __construct($logger) {
            $this->_logger = $logger;
            $this->_mysql_connector = MysqlConnector::getInstance();
            $this->_module_dao = ModuleDao::getInstance();
        }

        abstract function getIdentifier();
        abstract function getTitle();
        abstract function getStaticDirectory();
        abstract function getBackendTemplateDirectory();
        abstract function getModuleIconPath();
        abstract function getModuleGroup();
        abstract function isPopup();
        abstract function getActivatorClassName();
        abstract function getInstallQueries();
        abstract function getUninstallQueries();

        public function install() {
            $this->_logger->log('Installer voor component \'' . $this->getTitle() . '\' gestart');
            $this->installModule();
            $this->installStaticFiles();
            $this->installBackendTemplates();
            $this->installModuleFiles();
        }

        public function unInstall() {
            echo 'Uninstall gestart!';
        }

        private function installModule() {
            $module = new Module();
            $module->setTitle($this->getTitle());
            $module->setIdentifier($this->getIdentifier());
            $module->setIconUrl($this->getModuleIconPath());
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

        private function installModuleFiles() {
            $target_dir = CMS_ROOT . 'modules/' . $this->getIdentifier();
            $this->createDir($target_dir);
            $this->_logger->log('Overige bestanden kopiëren naar ' . $target_dir);
            FileUtility::moveDirectoryContents(COMPONENT_TEMP_DIR, $target_dir);
        }

        private function installStaticFiles() {
            $source_dir = COMPONENT_TEMP_DIR . '/' . $this->getStaticDirectory();
            if ($this->getStaticDirectory() && file_exists($source_dir)) {
                $target_dir = STATIC_DIR . '/modules/' . $this->getIdentifier();
                $this->createDir($target_dir);
                $this->_logger->log('Statische bestanden kopiëren naar ' . $target_dir);
                FileUtility::moveDirectoryContents($source_dir, $target_dir, true);
            }
            else
                $this->_logger->log('Geen statische bestanden gevonden');
        }

        private function installBackendTemplates() {
            $source_dir = COMPONENT_TEMP_DIR . '/' . $this->getBackendTemplateDirectory();
            if ($this->getBackendTemplateDirectory() && file_exists($source_dir)) {
                $target_dir = BACKEND_TEMPLATE_DIR . '/modules/' . $this->getIdentifier();
                $this->createDir($target_dir);
                $this->_logger->log('Backend templates kopiëren naar ' . $target_dir);
                FileUtility::moveDirectoryContents($source_dir, $target_dir, true);
            } else
                $this->_logger->log('Geen backend templates gevonden');
        }

        private function runInstallQueries() {
            $this->_logger->log('Installtiequeries uitvoeren');
            $queries = $this->getInstallQueries();
            if (!is_array($queries)) return;
            foreach ($queries as $query) {
                $this->_logger->log('Query uitvoeren: ' . $query);
                $this->_mysql_connector->executeQuery($query);
            }
        }

        private function createDir($target_dir) {
            if (file_exists($target_dir))
                FileUtility::recursiveDelete($target_dir);
            else
                mkdir($target_dir);
        }

    }