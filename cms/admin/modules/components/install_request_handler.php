<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'view/request_handlers/module_request_handler.php';
    require_once CMS_ROOT . 'modules/components/install_component_form.php';
    require_once CMS_ROOT . 'notifications.php';
    require_once CMS_ROOT . 'utilities/file_utility.php';
    require_once CMS_ROOT . 'modules/components/api/installation_exception.php';

    class InstallRequestHandler extends ModuleRequestHandler {

        private $_log_messages = array();

        public function handleGet() {
        }

        public function handlePost() {
            if ($this->isInstallComponentAction())
                $this->installComponent();
        }

        public function getLogMessages() {
            return $this->_log_messages;
        }

        private function installComponent() {
            $form = new InstallComponentForm();
            try {
                $form->loadFields();
                $this->handleComponentZip($form->getFilePath());
            } catch (FormException $e) {
                Notifications::setFailedMessage('U dient een component archief te kiezen');
            } catch (InstallationException $e) {
                Notifications::setFailedMessage('Installatie van component mislukt');
            }
        }

        private function handleComponentZip($file_path) {
            $zip_archive = new ZipArchive();
            $zip = $zip_archive->open($file_path);
            try {
                if (is_numeric($zip)) {
                    $this->log('Invalide ZIP archief');
                    throw new InstallationException();
                }
                $this->log('ZIP archief gevonden');
                $this->extractZip($zip_archive);
                $this->runInstaller();
            } finally {
                $zip_archive->close();
                FileUtility::recursiveDelete(COMPONENT_TEMP_DIR);
            }
        }

        private function runInstaller() {
            $this->checkInstallerFileProvided();
            require_once COMPONENT_TEMP_DIR . '/installer.php';
            $this->checkIfValidInstallerClassIsProvided();
            $installer = new CustomModuleInstaller();
            $this->log('Installer uitvoeren');
            $installer->install();
        }

        private function checkIfValidInstallerClassIsProvided() {
            if (!class_exists('CustomModuleInstaller')) {
                $this->log('Class CustomModuleInstaller niet gevonden');
                throw new InstallationException();
            }
            $this->log('CustomModuleInstaller class gevonden');
        }

        private function checkInstallerFileProvided() {
            if (!file_exists(COMPONENT_TEMP_DIR . '/installer.php')) {
                $this->log('installer.php bestand niet gevonden');
                throw new InstallationException();
            }
            $this->log('installer.php bestand gevonden');
        }

        private function extractZip($zip_archive) {
            if (!file_exists(COMPONENT_TEMP_DIR)) mkdir(COMPONENT_TEMP_DIR);
            $this->log('ZIP archief uitpakken naar ' . COMPONENT_TEMP_DIR);
            $zip_archive->extractTo(COMPONENT_TEMP_DIR);
        }

        private function isInstallComponentAction() {
            return isset($_POST['action']) && $_POST['action'] == 'install_component';
        }

        private function log($message) {
            $this->_log_messages[] = date('H:m:s') . ': ' . $message;
        }
    }