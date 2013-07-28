<?php
	// No direct access
	defined('_ACCESS') or die;
	
	require_once "view/views/visual.php";
	require_once "view/views/information_message.php";
	require_once "view/views/form_checkbox_single.php";
	require_once "dao/image_dao.php";
	
	class LabelsList extends Visual {
	
		private static $TEMPLATE = "images/labels/list.tpl";
		
		private $_template_engine;
		private $_image_dao;
		
		public function __construct() {
			$this->_template_engine = TemplateEngine::getInstance();
			$this->_image_dao = ImageDao::getInstance();
		}
	
		public function render() {
			$this->_template_engine->assign("all_labels", $this->getAllLabels());
			$this->_template_engine->assign("no_labels_message", $this->getNoLabelsMessage());
			return $this->_template_engine->fetch("modules/" . self::$TEMPLATE);
		}
		
		private function getAllLabels() {
			$label_values = array();
			$all_labels = $this->_image_dao->getAllLabels();
			foreach ($all_labels as $label) {
				$label_values[] = $this->createLabelValue($label);
			}
			return $label_values;
		}
		
		private function getNoLabelsMessage() {
			$message = new InformationMessage("Geen labels gevonden");
			return $message->render();
		}
		
		private function createLabelValue($label) {
			$label_value = array();
			$label_value["id"] = $label->getId();
			$label_value["name"] = $label->getName();
			$label_value["delete_checkbox"] = $this->getDeleteCheckBox($label);
			return $label_value;
		}
		
		private function getDeleteCheckBox($label) {
			$checkbox = new SingleCheckBox("label_" . $label->getId() . "_delete", "", 0, false, "");
			return $checkbox->render();
		}
		
	}