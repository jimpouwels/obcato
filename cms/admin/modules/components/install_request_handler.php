<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'view/request_handlers/module_request_handler.php';
    require_once CMS_ROOT . 'modules/components/install_component_form.php';
    require_once CMS_ROOT . 'notifications.php';
    require_once CMS_ROOT . 'utilities/file_utility.php';

    class InstallRequestHandler extends ModuleRequestHandler {

        public function handleGet() {
        }

        public function handlePost() {
            if ($this->isInstallComponentAction())
                $this->installComponent();
        }

        private function installComponent() {
            $form = new InstallComponentForm();
            try {
                $form->loadFields();
                $this->handleComponentZip($form->getFilePath());
            } catch (FormException $e) {
                Notifications::setFailedMessage('U dient een component archief te kiezen');
            } catch (InvalidComponentArchiveException $e) {
                Notifications::setFailedMessage('Dit is geen geldig ZIP archief');
            } catch (InstallerNotFoundException $e) {
                Notifications::setFailedMessage('Er is geen installer.php bestand gevonden');
            } catch (InstallerClassNotFoundException $e) {
                Notifications::setFailedMessage('Class niet gevonden in installer.php');
            }
        }

        private function handleComponentZip($file_path) {
            $zip_archive = new ZipArchive();
            $zip = $zip_archive->open($file_path);
            try {
                if (is_numeric($zip)) throw new InvalidComponentArchiveException('Invalid ZIP archive');
                $this->extractZip($zip_archive);
                $this->runInstaller();
            } finally {
                $zip_archive->close();
                FileUtility::recursiveDelete(COMPONENT_TEMP_DIR);
            }
        }

        private function runInstaller() {
            if (!file_exists(COMPONENT_TEMP_DIR . '/installer.php'))
                throw new InstallerNotFoundException('Installer file could not be found');
            require_once COMPONENT_TEMP_DIR . '/installer.php';
            if (!class_exists('CustomModuleInstaller'))
                throw new InstallerClassNotFoundException('Class CustomModuleInstaller not found');
            $installer = new CustomModuleInstaller();
            echo $installer->getTitle();
        }

        private function extractZip($zip_archive) {
            if (!file_exists(COMPONENT_TEMP_DIR)) mkdir(COMPONENT_TEMP_DIR);
            $zip_archive->extractTo(COMPONENT_TEMP_DIR);
        }

        private function isInstallComponentAction() {
            return isset($_POST['action']) && $_POST['action'] == 'install_component';
        }
    }

    class InvalidComponentArchiveException extends Exception {

        public function __construct($message = "") {
            parent::__construct($message);
        }
    }

    class InstallerNotFoundException extends Exception {

        public function __construct($message = "") {
            parent::__construct($message);
        }
    }

class InstallerClassNotFoundException extends Exception {

    public function __construct($message = "") {
        parent::__construct($message);
    }
}