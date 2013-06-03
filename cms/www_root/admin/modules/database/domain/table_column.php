<?php
	// No direct access
	defined('_ACCESS') or die;
	
	class TableColumn {
		
		private $myName;
		private $myType;
		private $myAllowedNull;
		
		public function getName() {
			return $this->myName;
		}
		
		public function setName($name) {
			$this->myName = $name;
		}
		
		public function getType() {
			return $this->myType;
		}
		
		public function setType($type) {
			$this->myType = $type;
		}
		
		public function getAllowedNull() {
			return $this->myAllowedNull;
		}
		
		public function setAllowedNull($allowed_null) {
			$this->myAllowedNull = $allowed_null;
		}
	
	}
	
?>