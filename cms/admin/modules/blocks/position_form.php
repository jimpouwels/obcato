<?php

	defined("_ACCESS") or die;
	
	require_once CMS_ROOT . "request_handlers/form.php";
	
	class PositionForm extends Form {
	
		private $_position;
		private $_block_dao;
	
		public function __construct($position) {
			$this->_position = $position;
			$this->_block_dao = BlockDao::getInstance();
		}
	
		public function loadFields() {
            $positionName = str_replace(" ", "_", $this->getMandatoryFieldValue("name", "Naam is verplicht"));
			$this->_position->setName($positionName);
			if ($this->hasErrors() || $this->positionAlreadyExists())
				throw new FormException();
		}
		
		private function positionAlreadyExists() {
			$existing_pos = $this->_block_dao->getBlockPositionByName($this->_position->getName());
			if (!is_null($existing_pos) && $existing_pos->getId() != $this->_position->getId()) {
				$this->raiseError("name", "Er bestaat al een positie met deze naam");
				return true;
			}
			return false;
		}
		
	}
	