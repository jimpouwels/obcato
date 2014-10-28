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
            } catch (FormException $e) {
                Notifications::setFailedMessage('U dient een component archief te kiezen');
            }
        }

        private function isInstallComponentAction() {
            return isset($_POST['action']) && $_POST['action'] == 'install_component';
        }
    }