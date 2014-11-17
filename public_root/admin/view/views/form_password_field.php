<?php
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/form_textfield.php";
    
    class PasswordField extends TextField {
    
        private static $TEMPLATE = "system/form_password.tpl";
        private $_template_engine;

        public function __construct($name, $label, $value, $mandatory, $class_name) {
            parent::__construct($name, $label, $value, $mandatory, false, $class_name);
            $this->_template_engine = TemplateEngine::getInstance();
        }
    
        public function render() {
            parent::render();
            return $this->_template_engine->fetch(self::$TEMPLATE);
        }
    
    }

?>
