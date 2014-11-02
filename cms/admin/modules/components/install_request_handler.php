<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'request_handlers/module_request_handler.php';
    require_once CMS_ROOT . 'modules/components/install_component_form.php';
    require_once CMS_ROOT . 'utilities/file_utility.php';
    require_once CMS_ROOT . 'modules/components/installer/installation_exception.php';
    require_once CMS_ROOT . 'modules/components/installer/logger.php';

    class InstallRequestHandler extends ModuleRequestHandler {

        private $_logger;

        public function __construct() {
            $this->_logger = new Logger();
        }

        public function handleGet() {
        }

        public function handlePost() {
            if ($this->isInstallComponentAction())
                $this->installComponent();
        }

        public function getLogMessages() {
            return $this->_logger->getLogMessages();
        }

        private function installComponent() {
            $form = new InstallComponentForm();
            try {
                $form->loadFields();
                $this->handleComponentZip($form->getFilePath());
            } catch (FormException $e) {
                $this->sendErrorMessage('U dient een component archief te kiezen');
            } catch (InstallationException $e) {
                $this->sendErrorMessage('Installatie van component mislukt');
            }
        }

        private function handleComponentZip($file_path) {
            $zip_archive = new ZipArchive();
            $zip = $zip_archive->open($file_path);
            try {
                $this->checkIfFileIsZip($zip);
                $this->_logger->log('ZIP archief gevonden');
                $this->extractZip($zip_archive);
                $this->runInstaller();
            } finally {
                $zip_archive->close();
                $this->_logger->log('Tijdelijke bestanden opruimen');
                FileUtility::recursiveDelete(COMPONENT_TEMP_DIR);
            }
            $this->_logger->log('Installatie succesvol afgerond');
        }

        private function runInstaller() {
            $this->checkInstallerFileProvided();
            require_once COMPONENT_TEMP_DIR . '/installer.php';
            $this->checkIfValidInstallerClassIsProvided();
            $installer = new CustomModuleInstaller($this->_logger);
            $this->checkIfInstallerIsOfCorrectType($installer);
            $this->_logger->log('Installer uitvoeren');
            $installer->install();
        }

        private function checkIfValidInstallerClassIsProvided() {
            if (!class_exists(ModuleInstaller::$CUSTOM_INSTALLER_CLASSNAME)) {
                $this->_logger->log('Class ' . ModuleInstaller::$CUSTOM_INSTALLER_CLASSNAME . ' niet gevonden');
                throw new InstallationException();
            }
            $this->_logger->log(ModuleInstaller::$CUSTOM_INSTALLER_CLASSNAME . ' class gevonden');
        }

        private function checkIfInstallerIsOfCorrectType($installer) {
            if (!$installer instanceof ModuleInstaller) {
                $this->_logger->log('Installer class moet een implementatie zijn van ModuleInstaller');
                throw new InstallationException();
            }
        }

        private function checkInstallerFileProvided() {
            if (!file_exists(COMPONENT_TEMP_DIR . '/installer.php')) {
                $this->_logger->log('installer.php bestand niet gevonden');
                throw new InstallationException();
            }
            $this->_logger->log('installer.php bestand gevonden');
        }

        private function checkIfFileIsZip($zip) {
            if (is_numeric($zip)) {
                $this->_logger->log('Invalide ZIP archief');
                throw new InstallationException();
            }
        }

        private function extractZip($zip_archive) {
            if (!file_exists(COMPONENT_TEMP_DIR)) mkdir(COMPONENT_TEMP_DIR);
            $this->_logger->log('ZIP archief uitpakken naar ' . COMPONENT_TEMP_DIR);
            $zip_archive->extractTo(COMPONENT_TEMP_DIR);
        }

        private function isInstallComponentAction() {
            return isset($_POST['action']) && $_POST['action'] == 'install_component';
        }
    }