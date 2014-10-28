<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'view/request_handlers/module_request_handler.php';
    require_once CMS_ROOT . 'modules/components/install_component_form.php';
    require_once CMS_ROOT . 'notifications.php';

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
            }
        }

        private function handleComponentZip($file_path) {
            $resource = zip_open($file_path);
            if (is_numeric($resource)) throw new InvalidComponentArchiveException("Invalid ZIP archive");
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