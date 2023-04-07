<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'authentication/session.php';

    abstract class FormField extends Visual {
        
        private static $TEMPLATE = "system/form_field.tpl";

        private $_css_class;
        private $_field_name;
        private $_label;
        private $_mandatory;
        private $_linkable;
        private $_value;

        protected function __construct($field_name, $value, $label, $mandatory, $linkable, $css_class) {
            parent::__construct();
            $this->_field_name = $field_name;
            $this->_css_class = $css_class;
            $this->_label = $label;
            $this->_mandatory = $mandatory;
            $this->_linkable = $linkable;
            $this->_value = $value;
        }

        public function renderVisual(): string {
            $this->getTemplateEngine()->assign('classes',$this->getCssClassesHtml());
            $this->getTemplateEngine()->assign('label',$this->getInputLabelHtml($this->_label, $this->_field_name, $this->_mandatory));
            $this->getTemplateEngine()->assign("field_name", $this->_field_name);
            if (isset($_POST[$this->_field_name])) {
                $this->getTemplateEngine()->assign("field_value", StringUtility::escapeXml($_POST[$this->_field_name]));
            } else {
                $this->getTemplateEngine()->assign("field_value", StringUtility::escapeXml($this->_value));
            }
            $this->getTemplateEngine()->assign("error", $this->getErrorHtml($this->_field_name));
            return $this->getTemplateEngine()->fetch(self::$TEMPLATE);
        }

        public function getInputLabelHtml($field_label, $field_name, $mandatory) {
            $this->getTemplateEngine()->assign("label", $field_label);
            $this->getTemplateEngine()->assign("name", $field_name);
            $this->getTemplateEngine()->assign("mandatory", $mandatory);
            return $this->getTemplateEngine()->fetch("system/form_label.tpl");
        }
        
        public function getErrorHtml($field_name) {
            $error_html = "";
            if (Session::hasError($field_name)) {
                $this->getTemplateEngine()->assign("error", Session::popError($field_name));
                $error_html = $this->getTemplateEngine()->fetch("system/form_error.tpl");
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
            if (Session::hasError($field_name))
                return "invalid ";
        }
    }

?>