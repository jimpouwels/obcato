<?php
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/form_field.php";
    
    class Pulldown extends FormField {
    
        private static $TEMPLATE = "system/form_pulldown.tpl";
    
        private $_template_engine;
        private $_options;
    
        public function __construct($name, $label, $value, $options, $mandatory, $class_name) {
            parent::__construct($name, $value, $label, $mandatory, false, $class_name);
            $this->_template_engine = TemplateEngine::getInstance();
            $this->_options = $options;
        }
    
        public function render() {
            parent::render();
            $this->_template_engine->assign("options", $this->_options);
            return $this->_template_engine->fetch(self::$TEMPLATE);
        }
    
    }

?>