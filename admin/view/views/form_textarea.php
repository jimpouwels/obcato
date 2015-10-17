<?php
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/form_field.php";
    
    class TextArea extends FormField {
    
        private static $TEMPLATE = "system/form_textarea.tpl";

        private $_columns;
        private $_rows;
        private $_template_engine;
    
        public function __construct($name, $label, $value, $cols, $rows, $mandatory, $linkable, $class_name) {
            parent::__construct($name, $value, $label, $mandatory, $linkable, $class_name);
            $this->_columns = $cols;
            $this->_rows = $rows;
            $this->_template_engine = TemplateEngine::getInstance();
        }
    
        public function render() {
            parent::render();
            $this->_template_engine->assign("columns", $this->_columns);
            $this->_template_engine->assign("rows", $this->_rows);
            return $this->_template_engine->fetch(self::$TEMPLATE);
        }
    
    }