<?php
    defined('_ACCESS') or die;

    class ComponentInstallFormPanel extends Panel {

        private static $TEMPLATE = 'installation/component_install_form.tpl';

        public function __construct() {
            parent::__construct('Instaleer component', 'install-form-panel');
        }

        public function renderVisual(): string {
            return parent::renderVisual();
        }

        public function renderPanelContent() {
            $this->getTemplateEngine()->assign('upload_field', $this->renderUploadField());
            return $this->getTemplateEngine()->fetch('modules/components/' . self::$TEMPLATE);
        }

        private function renderUploadField() {
            $upload_field = new UploadField('upload_field', 'Upload component', true, "");
            return $upload_field->render();
        }
    }
