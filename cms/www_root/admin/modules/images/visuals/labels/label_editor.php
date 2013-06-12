<?php
	// No direct access
	defined('_ACCESS') or die;
	
	require_once "core/visual/visual.php";
	require_once "core/visual/form_textfield.php";
	
	class LabelEditor extends Visual {
	
		private static $TEMPLATE = "images/labels/label_editor.tpl";
		
		private $_template_engine;
		private $_current_label;
		
		public function __construct($current_label) {
			$this->_current_label = $current_label;
			$this->_template_engine = TemplateEngine::getInstance();
		}
		
		public function render() {
			$this->_template_engine->assign("id", $this->_current_label->getId());
			$this->_template_engine->assign("label_name_field", $this->renderLabelNameField());
			return $this->_template_engine->fetch("modules/" . self::$TEMPLATE);
		}
		
		private function renderLabelNameField() {
			$name_field = new TextField("name", "Naam", $this->_current_label->getName(), true, false, null);
			return $name_field->render();
		}
		
	}