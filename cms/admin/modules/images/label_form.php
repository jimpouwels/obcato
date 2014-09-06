<?php

	defined("_ACCESS") or die;
	
	require_once "pre_handlers/form.php";
	require_once "database/dao/image_dao.php";
	
	class LabelForm extends Form {
	
		private $_label;
		private $_image_dao;
	
		public function __construct($label) {
			$this->_label = $label;
			$this->_image_dao = ImageDao::getInstance();
		}
	
		public function loadFields() {
			$this->_label->setName($this->getMandatoryFieldValue("name", "Naam is verplicht"));
			if ($this->hasErrors() || $this->labelExists())
				throw new FormException();
		}
		
		public function getSelectedLabels() {
			return $this->_selected_labels;
		}
		
		private function labelExists() {
			$existing_label = $this->_image_dao->getLabelByName($this->_label->getName());
			if (!is_null($existing_label) && $existing_label->getId() != $this->_label->getId()) {
				$this->raiseError("name", "Er bestaat al een label met deze naam");
				return true;
			}
			return false;
		}
		
	}
	