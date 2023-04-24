<?php
    defined('_ACCESS') or die;

    class ComponentInstallFormPanel extends Panel {

        public function __construct() {
            parent::__construct('Instaleer component', 'install-form-panel');
        }

        public function getPanelContentTemplate(): string {
            return 'modules/components/installation/component_install_form.tpl';
        }

        public function loadPanelContent(Smarty_Internal_Data $data): void {
            $data->assign('upload_field', $this->renderUploadField());
        }

        private function renderUploadField() {
            $upload_field = new UploadField('upload_field', 'Upload component', true, "");
            return $upload_field->render();
        }
    }
