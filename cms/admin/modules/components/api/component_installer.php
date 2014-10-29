<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'utilities/file_utility.php';

    abstract class ComponentInstaller {

        public static $CUSTOM_INSTALLER_CLASSNAME = 'CustomModuleInstaller';
        private $_logger;

        public function __construct($logger) {
            $this->_logger = $logger;
        }

        abstract function getIdentifier();
        abstract function getTitle();
        abstract function getStaticDirectory();
        abstract function getBackendTemplateDirectory();

        public function install() {
            $this->_logger->log('Installer voor component \'' . $this->getTitle() . '\' gestart');
            $this->installStaticFiles();
            $this->installBackendTemplates();
            $this->installModuleFiles();
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

        private function createDir($target_dir) {
            if (file_exists($target_dir))
                FileUtility::recursiveDelete($target_dir);
            else
                mkdir($target_dir);
        }

    }