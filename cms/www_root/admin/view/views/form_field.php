<?php

	// No direct access
	defined('_ACCESS') or die;
	
	require_once FRONTEND_REQUEST . "view/views/visual.php";
	require_once FRONTEND_REQUEST . "view/template_engine.php";
	
	abstract class FormField extends Visual {
		
		public function getInputLabelHtml($field_label, $field_name, $mandatory) {
			$template_engine = TemplateEngine::getInstance();
			$template_engine->assign("label", $field_label);
			$template_engine->assign("name", $field_name);
			$template_engine->assign("mandatory", $mandatory);
			return $template_engine->fetch("system/form_label.tpl");
		}
		
		public function getErrorHtml($field_name) {
			$error_html = "";
			if ($this->hasError($field_name)) {
				$template_engine = TemplateEngine::getInstance();
				$template_engine->assign("error", $_SESSION["errors"][$field_name . '_error']);
				$error_html = $template_engine->fetch("system/form_error.tpl");
			}
			return $error_html;
		}
		
		public function errorClass($field_name) {
			$error_class_value = "";
			if ($this->hasError($field_name)) {
				$error_class_value = "invalid";
			}
			return $error_class_value;
		}
		
		public function getCssClassesHtml($css_classes) {
			$css_class_html = "";
			
			foreach ($css_classes as $css_class) {
				if (!is_null($css_class) && $css_class != "") {
					$css_class_html = $css_class_html . $css_class . " ";
				}
			}
			$css_class_html = trim($css_class_html);
			
			return $css_class_html;
		}
		
		private function hasError($field_name) {
			return isset($_SESSION["errors"][$field_name . "_error"]);
		}
	}

?>