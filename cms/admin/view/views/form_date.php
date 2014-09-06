<?php

	// No direct access
	defined('_ACCESS') or die;
	
	require_once CMS_ROOT . "view/views/form_field.php";
	
	class DateField extends FormField {
	
		private static $TEMPLATE = "system/form_date.tpl";
		private static $DATE_PICKER_TEMPLATE = "system/datepicker.tpl";
	
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
			if (isset($_POST[$this->myName]) && $_POST[$this->myName] != "") {
				$this->myValue = StringUtility::unescapeXml($_POST[$this->myName]);
			}
		
			$css_classes = array();
			array_push($css_classes, $this->myClassName);
			$error_class = $this->errorClass($this->myName);
			if (!is_null($error_class) && $error_class != '') {
				array_push($css_classes, $error_class);				
			}
			
			$template_engine = TemplateEngine::getInstance();
			$template_engine->assign("field_value", StringUtility::escapeXml($this->myValue));
			$template_engine->assign("field_name", $this->myName);
			$template_engine->assign("error", $this->getErrorHtml($this->myName));
			$template_engine->assign("label", $this->getInputLabelHtml($this->myLabel, $this->myName, $this->myMandatory));
			$template_engine->assign("classes", $this->getCssClassesHtml($css_classes));
			$template_engine->assign("field_name", $this->myName);
			
			return $template_engine->fetch(self::$TEMPLATE);
		}
	
	}

?>