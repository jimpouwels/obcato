<?php

	// No direct access
	defined('_ACCESS') or die;
	
	require_once CMS_ROOT . "view/views/form_field.php";
	
	class TextArea extends FormField {
	
		private static $TEMPLATE = "system/form_textarea.tpl";
	
		private $myName;
		private $myLabel;
		private $myColumns;
		private $myRows;
		private $myValue;
		private $myMandatory;
		private $myLinkable;
		private $myClassName;
	
		public function __construct($name, $label, $value, $cols, $rows, $mandatory, $linkable, $class_name) {
			$this->myName = $name;
			$this->myLabel = $label;
			$this->myColumns = $cols;
			$this->myRows = $rows;
			$this->myValue = $value;
			$this->myMandatory = $mandatory;
			$this->myLinkable = $linkable;
			$this->myClassName = $class_name;
		}
	
		public function render() {
			if (isset($_POST[$this->myName])) {
				$field_value = StringUtility::unescapeXml($_POST[$this->myName]);
			}
			
			$css_classes = array();
			if ($this->myLinkable) {
				array_push($css_classes, 'linkable');
			}
			$error_class = self::errorClass($this->myName);
			if (!is_null($error_class) && $error_class != '') {
				array_push($css_classes, $error_class);				
			}
			
			$template_engine = TemplateEngine::getInstance();
			$template_engine->assign("field_name", $this->myName);
			$template_engine->assign("field_value", StringUtility::escapeXml($this->myValue));
			$template_engine->assign("error", self::getErrorHtml($this->myName));
			$template_engine->assign("label", self::getInputLabelHtml($this->myLabel, $this->myName, $this->myMandatory));
			$template_engine->assign("classes", self::getCssClassesHtml($css_classes));
			$template_engine->assign("columns", $this->myColumns);
			$template_engine->assign("rows", $this->myRows);
			
			return $template_engine->fetch(self::$TEMPLATE);
		}
	
	}

?>