<?php
    defined('_ACCESS') or die;

    abstract class FormField extends Visual {

        private $_template_engine;
        private $_css_class;
        private $_field_name;
        private $_label;
        private $_mandatory;
        private $_linkable;
        private $_value;

        protected function __construct($field_name, $value, $label, $mandatory, $linkable, $css_class) {
            $this->_field_name = $field_name;
            $this->_css_class = $css_class;
            $this->_label = $label;
            $this->_mandatory = $mandatory;
            $this->_linkable = $linkable;
            $this->_value = $value;
            $this->_template_engine = TemplateEngine::getInstance();
        }

        public function render() {
            $this->_template_engine->assign('classes',$this->getCssClassesHtml());
            $this->_template_engine->assign('label',$this->getInputLabelHtml($this->_label, $this->_field_name, $this->_mandatory));
            $this->_template_engine->assign("field_name", $this->_field_name);
            if (isset($_POST[$this->_field_name]))
                $this->_template_engine->assign("field_value", StringUtility::escapeXml($_POST[$this->_field_name]));
            else
                $this->_template_engine->assign("field_value", StringUtility::escapeXml($this->_value));
            $this->_template_engine->assign("error", $this->getErrorHtml($this->_field_name));
        }

        public function getInputLabelHtml($field_label, $field_name, $mandatory) {
            $this->_template_engine->assign("label", $field_label);
            $this->_template_engine->assign("name", $field_name);
            $this->_template_engine->assign("mandatory", $mandatory);
            return $this->_template_engine->fetch("system/form_label.tpl");
        }
        
        public function getErrorHtml($field_name) {
            global $errors;
            $error_html = "";
            if ($this->hasError($field_name)) {
                $template_engine = TemplateEngine::getInstance();
                $template_engine->assign("error", $errors[$field_name . '_error']);
                $error_html = $template_engine->fetch("system/form_error.tpl");
            }
            return $error_html;
        }
        
        public function getCssClassesHtml() {
            $css_class_html = $this->_css_class;
            $css_class_html .= ' ' . $this->errorClass($this->_field_name);
            if ($this->_linkable)
                $css_class_html .= 'linkable ';
            $css_class_html = trim($css_class_html);
            return $css_class_html;
        }

        public function errorClass($field_name) {
            if ($this->hasError($field_name))
                return "invalid ";
        }
        
        private function hasError($field_name) {
            global $errors;
            return isset($errors[$field_name . "_error"]) && $errors[$field_name . "_error"];
        }
    }

?>