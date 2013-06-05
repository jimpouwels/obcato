<?php

	// No direct access
	defined('_ACCESS') or die;
	
	include_once FRONTEND_REQUEST . "libraries/utilities/string_utility.php";
	include_once FRONTEND_REQUEST . "libraries/system/template_engine.php";
	
	class FormRenderer {
		
		/*
			Private constructor.
		*/
		private function __construct() {
		}
		
		/*
			Renders a regular form text field.
			
			@param $field_label The field label to render
			@param $field_value The default field value
			@param $class_name The class name of the field
		*/
		public static function renderText($field_label, $field_value, $class_name) {
			$template_engine = TemplateEngine::getInstance();
			$template_engine->assign("label", $field_label);
			$template_engine->assign("field_value", StringUtility::escapeXml($field_value));
			
			$template_engine->display("form_text.tpl");
		}
	}
	
?>