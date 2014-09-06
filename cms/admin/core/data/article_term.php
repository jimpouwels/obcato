<?php

	// No direct access
	defined('_ACCESS') or die;

	include_once "core/data/entity.php";
	include_once "database/dao/article_dao.php";

	class ArticleTerm extends Entity {
	
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
			return null;
		}

	}