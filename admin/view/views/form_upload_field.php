<?php
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/form_field.php";
    
    class UploadField extends FormField {
    
        private static $TEMPLATE = "system/form_upload_field.tpl";
        private $_template_engine;

        public function __construct($name, $label, $mandatory, $class_name) {
            parent::__construct($name, null, $label, $mandatory, false, $class_name);
            $this->_template_engine = TemplateEngine::getInstance();
        }
    
        public function renderVisual(): string {
            parent::renderVisual();
            return $this->_template_engine->fetch(self::$TEMPLATE);
        }
    
    }