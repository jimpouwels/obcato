<?php

	// No direct access
	defined('_ACCESS') or die;
	
	require_once CMS_ROOT . "view/views/form_field.php";
	
	class Pulldown extends FormField {
	
		private static $TEMPLATE = "system/form_pulldown.tpl";
	
		private $_name;
		private $_label;
		private $_value;
		private $_options;
		private $_mandatory;
		private $_classname;
	
		public function __construct($name, $label, $value, $options, $mandatory, $class_name) {
			$this->_name = $name;
			$this->_label = $label;
			$this->_value = $value;
			$this->_options = $options;
			$this->_mandatory = $mandatory;
			$this->_classname = $class_name;
		}
	
		public function render() {
			if (isset($_POST[$this->_name])) {
				$field_value = StringUtility::escapeXml($_POST[$this->_name]);
			}
			
			$css_classes = array();
			array_push($css_classes, $this->_classname);
			$error_class = $this->errorClass($this->_name);
			if (!is_null($error_class) && $error_class != '') {
				array_push($css_classes, $error_class);				
			}
			
			$template_engine = TemplateEngine::getInstance();
			
			$template_engine->assign("field_name", $this->_name);
			$template_engine->assign("options", $this->_options);
			$template_engine->assign("field_value", $this->_value);
			$template_engine->assign("error", $this->getErrorHtml($this->_name));
			$template_engine->assign("label", $this->getInputLabelHtml($this->_label, $this->_name, $this->_mandatory));
			$template_engine->assign("classes", $this->getCssClassesHtml($css_classes));
			
			return $template_engine->fetch(self::$TEMPLATE);
		}
	
	}

?>