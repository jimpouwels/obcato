<?php

	// No direct access
	defined('_ACCESS') or die;

	abstract class Entity {
	
		private $_id;
		
		public function getId() {
			return $this->_id;
		}
		
		public function setId($id) {
			$this->_id = $id;
		}
	
	}
	
?>