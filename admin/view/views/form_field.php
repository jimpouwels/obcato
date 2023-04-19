<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'authentication/session.php';

    abstract class FormField extends Visual {
        
        private static string $TEMPLATE = "system/form_field.tpl";

        private ?string $_css_class = null;
        private string $_field_name;
        private string $_label;
        private bool $_mandatory;
        private bool $_linkable;
        private ?string $_value = null;

        protected function __construct(string $field_name, ?string $value, string $label, bool $mandatory, bool $linkable, ?string $css_class) {
            parent::__construct();
            $this->_field_name = $field_name;
            $this->_css_class = $css_class;
            $this->_label = $label;
            $this->_mandatory = $mandatory;
            $this->_linkable = $linkable;
            $this->_value = $value;
        }

        public function render(): string {
            $this->getTemplateEngine()->assign('classes',$this->getCssClassesHtml());
            $this->getTemplateEngine()->assign('label', $this->getLabelHtml());
            $this->getTemplateEngine()->assign("field_name", $this->_field_name);
            $this->getTemplateEngine()->assign("field_value", $this->getFieldValue());
            $this->getTemplateEngine()->assign("error", $this->getErrorHtml($this->_field_name));
            return $this->getTemplateEngine()->fetch(self::$TEMPLATE);
        }
        
        public function getErrorHtml(string $field_name): string {
            $error_html = "";
            if (Session::hasError($field_name)) {
                $this->getTemplateEngine()->assign("error", Session::popError($field_name));
                $error_html = $this->getTemplateEngine()->fetch("system/form_error.tpl");
            }
            return $error_html;
        }
        
        public function getCssClassesHtml(): string {
            $css_class_html = $this->_css_class;
            $css_class_html .= ' ' . $this->errorClass($this->_field_name);
            if ($this->_linkable) {
                $css_class_html .= 'linkable ';
            }
            $css_class_html = trim($css_class_html);
            return $css_class_html;
        }

        public function errorClass(string $field_name): string {
            if (Session::hasError($field_name)) {
                return "invalid ";
            }
            return "";
        }

        protected function getInputLabelHtml(string $field_label, string $field_name, bool $mandatory) {
            $data = $this->getTemplateEngine()->createData();
            $data->assign("label", $field_label);
            $data->assign("name", $field_name);
            $data->assign("mandatory", $mandatory);
            $tpl = $this->getTemplateEngine()->createTemplate("system/form_label.tpl", $data);
            return $tpl->fetch();
        }

        private function getFieldValue(): ?string {
            if (isset($_POST[$this->_field_name])) {
                return StringUtility::escapeXml($_POST[$this->_field_name]);
            } else {
                return StringUtility::escapeXml($this->_value);
            }
        }

        private function getLabelHtml(): ?string {
            if ($this->_label) {
                return $this->getInputLabelHtml($this->_label, $this->_field_name, $this->_mandatory);
            } else {
                return null;
            }
        }
    }

?>