<?php
    defined('_ACCESS') or die;

    class ComponentInstallFormVisual extends Visual {

        private static $TEMPLATE = 'component_install_form.tpl';
        private $_template_engine;
        private $_install_request_handler;

        public function __construct($install_request_handler) {
            $this->_install_request_handler = $install_request_handler;
            $this->_template_engine = TemplateEngine::getInstance();
        }

        public function render() {
            $this->_template_engine->assign('upload_field', $this->renderUploadField());
            return $this->_template_engine->fetch('modules/components/' . self::$TEMPLATE);
        }

        private function renderUploadField() {
            $upload_field = new UploadField('upload_field', 'Upload component', true, "");
            return $upload_field->render();
        }
    }