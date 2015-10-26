<?php
    defined('_ACCESS') or die;

    class ComponentInstallFormPanel extends Panel {

        private static $TEMPLATE = 'installation/component_install_form.tpl';
        private $_template_engine;

        public function __construct() {
            parent::__construct('Instaleer component', 'install-form-panel');
            $this->_template_engine = TemplateEngine::getInstance();
        }

        public function render() {
            return parent::render();
        }

        public function renderPanelContent() {
            $this->_template_engine->assign('upload_field', $this->renderUploadField());
            return $this->_template_engine->fetch('modules/components/' . self::$TEMPLATE);
        }

        private function renderUploadField() {
            $upload_field = new UploadField('upload_field', 'Upload component', true, "");
            return $upload_field->render();
        }
    }
