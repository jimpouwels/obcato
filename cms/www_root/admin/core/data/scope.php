<?php

	// No direct access
	defined('_ACCESS') or die;

	include_once FRONTEND_REQUEST . "core/data/entity.php";

	class Scope extends Entity {
	
		private static $TABLE_NAME = "scopes";
	
		private $_name;
		
		public function getName() {
			return $this->_name;
		}
		
		public function setName($name) {
			$this->_name = $name;
		}
		
		public function persist() {
		}
		
		public function update() {
		}
		
		public function delete() {
		}
		
		public static function constructFromRecord($record) {
			$scope = new Scope();
			$scope->setId($record['id']);
			$scope->setName($record['name']);
			
			return $scope;
		}
	
	}
	
?>