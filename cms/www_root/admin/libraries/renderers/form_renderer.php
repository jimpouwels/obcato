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
		
		/*
			Renders a file upload field.
			
			@param $field The name of the field
			@param $field_label The label of the field
			@param $mandatory Indicates whether the field should be mandatory
		*/
		public static function renderFileUpload($field_name, $field_label, $mandatory) {
			$field_value = NULL;
			if (isset($_POST[$field_name])) {
				$field_value = StringUtility::escapeXml($_POST[$field_name]);
			}
			
			$css_classes = array();
			$error_class = self::errorClass($field_name);
			if (!is_null($error_class) && $error_class != '') {
				array_push($css_classes, $error_class);				
			}
			
			$template_engine = TemplateEngine::getInstance();
			$template_engine->assign("field_name", $field_name);
			$template_engine->assign("field_value", StringUtility::escapeXml($field_value));
			$template_engine->assign("error", self::getErrorHtml($field_name));
			$template_engine->assign("label", self::getInputLabelHtml($field_label, $field_name, $mandatory));
			$template_engine->assign("classes", self::getCssClassesHtml($css_classes));
			
			$template_engine->display("form_file.tpl");			
		}
	}
	
?>