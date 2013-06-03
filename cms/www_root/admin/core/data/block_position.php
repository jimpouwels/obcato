<?php

	// No direct access
	defined('_ACCESS') or die;
	
	require_once FRONTEND_REQUEST . "core/data/entity.php";
	require_once FRONTEND_REQUEST . "dao/block_dao.php";

	class BlockPosition extends Entity {
	
		private static $TABLE_NAME = "block_position";
	
		private $_name;
		private $_explanation;
		private $_block_dao;
		
		public function __construct() {
			$this->_name = 'Nieuwe positie';
			$this->_block_dao = BlockDao::getInstance();
		}
		
		public function getName() {
			return $this->_name;
		}
		
		public function setName($name) {
			$this->_name = $name;
		}
		
		public function getExplanation() {
			return $this->_explanation;
		}
		
		public function setExplanation($explanation) {
			$this->_explanation = $explanation;
		}
		
		public function getBlocks() {
			return $this->_block_dao->getBlocksByPosition($this);
		}
		
		public function persist() {
		}
		
		public function update() {
		}
		
		public function delete() {
		}
		
		public static function constructFromRecord($record) {
			$position = new BlockPosition();
			$position->setId($record['id']);
			$position->setName($record['name']);
			$position->setExplanation($record['explanation']);
			
			return $position;
		}
	
	}
	
?>