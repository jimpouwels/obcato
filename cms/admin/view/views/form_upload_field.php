<?php

	// No direct access
	defined('_ACCESS') or die;
	
	require_once CMS_ROOT . "view/views/form_field.php";
	
	class UploadField extends FormField {
	
		private static $TEMPLATE = "system/form_upload_field.tpl";
	
		private $_name;
		private $_label;
		private $_is_mandatory;
		private $_classname;
	
		public function __construct($name, $label, $mandatory, $class_name) {
			$this->_name = $name;
			$this->_label = $label;
			$this->_is_mandatory = $mandatory;
			$this->_classname = $class_name;
		}
	
		public function render() {			
			$css_classes = array();
			array_push($css_classes, $this->_classname);
			
			$error_class = $this->errorClass($this->_name);
			if (!is_null($error_class) && $error_class != '') {
				array_push($css_classes, $error_class);				
			}
			
			$template_engine = TemplateEngine::getInstance();
			$template_engine->assign("field_name", $this->_name);
			$template_engine->assign("error", $this->getErrorHtml($this->_name));
			$template_engine->assign("label", $this->getInputLabelHtml($this->_label, $this->_name, $this->_is_mandatory));
			$template_engine->assign("classes", $this->getCssClassesHtml($css_classes));
			
			return $template_engine->fetch(self::$TEMPLATE);
		}
	
	}

?>