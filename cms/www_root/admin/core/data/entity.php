<?php

	// No direct access
	defined('_ACCESS') or die;

	abstract class Entity {
	
		private $myId;
		
		public function getId() {
			return $this->myId;
		}
		
		public function setId($id) {
			$this->myId = $id;
		}
	
	}
	
?>