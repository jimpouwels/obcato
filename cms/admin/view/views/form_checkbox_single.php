<?php

	
	defined('_ACCESS') or die;
	
	require_once CMS_ROOT . "view/views/form_field.php";
	
	class SingleCheckbox extends FormField {
	
		private static $TEMPLATE = "system/form_checkbox_single.tpl";
	
		private $myName;
		private $myLabel;
		private $myValue;
		private $myMandatory;
		private $myClassName;
	
		public function __construct($name, $label, $value, $mandatory, $class_name) {
			$this->myName = $name;
			$this->myLabel = $label;
			$this->myValue = $value;
			$this->myMandatory = $mandatory;
			$this->myClassName = $class_name;
		}
	
		public function render() {
			if (isset($_POST[$this->myName])) {
				$this->myValue = 1;
			}
			
			$css_classes = array();
			array_push($css_classes, $this->myClassName);
			$error_class = self::errorClass($this->myName);
			if (!is_null($error_class) && $error_class != '') {
				array_push($css_classes, $error_class);				
			}
			
			$template_engine = TemplateEngine::getInstance();
			$template_engine->assign("checked", $this->myValue);
			$template_engine->assign("field_name", $this->myName);
			$template_engine->assign("error", self::getErrorHtml($this->myName));
			$template_engine->assign("label", self::getInputLabelHtml($this->myLabel, $this->myName, $this->myMandatory));
			$template_engine->assign("classes", self::getCssClassesHtml($css_classes));
			
			
			return $template_engine->fetch(self::$TEMPLATE);
		}
	
	}

?>