<?php

	// No direct access
	defined('_ACCESS') or die;

	include_once "database/mysql_connector.php";
	include_once "database/dao/element_dao.php";
	include_once "database/dao/template_dao.php";
	include_once "core/data/block.php";
	include_once "core/data/block_position.php";
	include_once "database/dao/authorization_dao.php";

	class BlockDao {
	
		// Holds the list of columns that are to be collected
		public static $myAllColumns = "e.id, e.template_id, e.title, e.published, e.scope_id, 
					  e.created_at, e.created_by, e.type, b.position_id";
					  
		/*
			This DAO is a singleton, no constructur but
			a getInstance() method instead.
		*/
		private static $instance;
		
		/*
			Private constructor.
		*/
		private function __construct() {
		}
		
		/*
			Creates a new BlockDao instance.
		*/
		public static function getInstance() {
			if (!self::$instance) {
				self::$instance = new BlockDao();
			}
			return self::$instance;
		}
		
		/*
			Returns all blocks.
		*/
		public function getAllBlocks() {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT " . self::$myAllColumns . " FROM element_holders e, blocks b WHERE e.id = b.element_holder_id";
			$result = $mysql_database->executeSelectQuery($query);
			$blocks = array();
			
			while ($row = mysql_fetch_array($result)) {
				$block = Block::constructFromRecord($row);
				
				array_push($blocks, $block);
			}
			return $blocks;
		}
		
		/*
			Returns the blocks that have the given position.
			
			@param @position The position to find the blocks for
		*/
		public function getBlocksByPosition($position) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT " . self::$myAllColumns . " FROM element_holders e, blocks b WHERE b.position_id = " 
			          . $position->getId() . " AND e.id = b.element_holder_id";
			$result = $mysql_database->executeSelectQuery($query);
			$blocks = array();
			
			while ($row = mysql_fetch_array($result)) {
				$block = Block::constructFromRecord($row);
				
				array_push($blocks, $block);
			}
			return $blocks;
		}
		
		/*
			Returns all blocks with no position assigned.
		*/
		public function getBlocksWithoutPosition() {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT " . self::$myAllColumns . " FROM element_holders e, blocks b WHERE b.position_id IS  
					 NULL AND e.id = b.element_holder_id";
			$result = $mysql_database->executeSelectQuery($query);
			$blocks = array();
			
			while ($row = mysql_fetch_array($result)) {
				$block = Block::constructFromRecord($row);
				
				array_push($blocks, $block);
			}
			return $blocks;
		}
		
		/*
			Returns all blocks for the given page with the given position.
			
			@param $page The page where the blocks must be found for
			@param $position The position the blocks must have
		*/
		public function getBlocksByPageAndPosition($page, $position_name) {
			$mysql_database = MysqlConnector::getInstance();
			
			$query = "SELECT " . self::$myAllColumns . 
					 " FROM element_holders e, blocks b, blocks_pages bp, block_positions bps" . 
					 " WHERE e.id = b.element_holder_id" . 
					 " AND bp.block_id = e.id" . 
					 " AND bp.page_id = " . $page->getId() .
					 " AND bps.name = '" . $position_name . "'" . 
					 " AND b.position_id = bps.id";
			
			if (FRONTEND_REQUEST != "") {
				$query = $query . " AND e.published = 1";
			}
			
		    $result = $mysql_database->executeSelectQuery($query);
			$blocks = array();
			
			while ($row = mysql_fetch_array($result)) {
				$block = Block::constructFromRecord($row);
				
				array_push($blocks, $block);
			}
			return $blocks;
		}
		
		/*
			Returns all blocks for the given page.
			
			@param $page The page where the blocks must be found for
		*/
		public function getBlocksByPage($page) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT " . self::$myAllColumns . " FROM element_holders e, blocks b, blocks_pages bps WHERE e.id = b.element_holder_id
			          AND bps.page_id = " . $page->getId() . " AND bps.block_id = e.id";
			$result = $mysql_database->executeSelectQuery($query);
			$blocks = array();

			while ($row = mysql_fetch_array($result)) {
				$block = Block::constructFromRecord($row);
				
				array_push($blocks, $block);
			}
			return $blocks;
		}
		
		/*
			Returns all positions.
		*/
		public function getBlockPositions() {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT * FROM block_positions ORDER BY name";
			$result = $mysql_database->executeSelectQuery($query);
			$positions = array();
			
			while ($row = mysql_fetch_array($result)) {
				$position = BlockPosition::constructFromRecord($row);
				
				array_push($positions, $position);
			}
			
			return $positions;
		}
		
		/*
			Returns the position with the given ID.
			
			@param $position_id The ID of the position to find
		*/
		public function getBlockPosition($position_id) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$position = NULL;
			if (!is_null($position_id) && $position_id != '') {
				$query = "SELECT * FROM block_positions WHERE id = " . $position_id;
				$result = $mysql_database->executeSelectQuery($query);
				
				while ($row = mysql_fetch_array($result)) {
					$position = BlockPosition::constructFromRecord($row);
					
					break;
				}
			}
			
			return $position;
		}
		
		/*
			Returns the position with the given name.
			
			@param $position_name The name of the position to find
		*/
		public function getBlockPositionByName($position_name) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$position = NULL;
			if (!is_null($position_name) && $position_name != '') {
				$query = "SELECT * FROM block_positions WHERE name = '" . $position_name . "'";
				$result = $mysql_database->executeSelectQuery($query);
				
				while ($row = mysql_fetch_array($result)) {
					$position = BlockPosition::constructFromRecord($row);
					
					break;
				}
			}
			
			return $position;
		}
		
		/*
			Returns the block with the given ID
			
			@param $id The ID of the block to find
		*/
		public function getBlock($id) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT " . self::$myAllColumns . " FROM element_holders e, blocks b WHERE e.id = " . $id
					 . " AND e.id = b.element_holder_id";
			$result = $mysql_database->executeSelectQuery($query);
			$block = null;
			
			while ($row = mysql_fetch_array($result)) {
				$block = Block::constructFromRecord($row);
				break;
			}
			
			return $block;
		}
		
		/*
			Creates and persists a new block.
		*/
		public function createBlock() {
			$mysql_database = MysqlConnector::getInstance(); 
			$new_block = new Block();
			$new_block->setPublished(false);
			$new_block->setTitle('Nieuw block');
			
			$authorization_dao = AuthorizationDao::getInstance();
			$user = $authorization_dao->getUser($_SESSION['username']);
			$new_block->setCreatedById($user->getId());
			$new_block->setType(ELEMENT_HOLDER_BLOCK);
			
			$new_block->setScopeId(6);
			$new_id = $this->persistBlock($new_block);
			$new_block->setId($new_id);
			
			return $new_block;
		}
		
		/*
			Persists the given block.]
			
			@param $block The block to persist
		*/
		private function persistBlock($block) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$published_value = $block->isPublished();
			if (!isset($published_value) || $published_value == '') {
				$published_value = 0;
			}
			$query1 = "INSERT INTO element_holders (template_id, title, published, scope_id, created_at, created_by, type)
					   VALUES  (NULL, '" . $block->getTitle() . "', " . $published_value . ",
					   " . $block->getScopeId() . ", now(), " . $block->getCreatedBy()->getId() . ", '" . $block->getType() . "')";
		
			$mysql_database->executeQuery($query1);
			
			$new_id = mysql_insert_id();
			
			$query2 = "INSERT INTO blocks (position_id, element_holder_id) VALUES (NULL, " . $new_id . ")";
					   
			$mysql_database->executeQuery($query2);
			
			return $new_id;
		}
		
		/*
			Updates the given block.
			
			@param $block The block to update
		*/
		public function updateBlock($block) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "UPDATE blocks b, element_holders e SET e.title = '" . $block->getTitle() . "'
					 , e.published = " . $block->isPublished() . ", e.scope_id = " . $block->getScopeId();
			
			if ($block->getPositionId() != '' && !is_null($block->getPositionId())) {
				$query = $query . ", b.position_id = " . $block->getPositionId();
			} else {
				$query = $query . ", b.position_id = NULL";
			}
			if ($block->getTemplateId() != '' && !is_null($block->getTemplateId())) {
				$query = $query . ", e.template_id = " . $block->getTemplateId();
			}
			$query = $query . " WHERE e.id = " . $block->getId() . " AND e.id = b.element_holder_id";

			$mysql_database->executeQuery($query);
		}
		
		/*
			Deletes the given block.
			
			@param $block The  block to delete
		*/
		public function deleteBlock($block) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "DELETE FROM element_holders WHERE id = " . $block->getId();
			
			$element_dao = ElementDao::getInstance();
			foreach ($block->getElements() as $element) {
				$element_dao->deleteElement($element);
			}
			
			$mysql_database->executeQuery($query);
		}
		
		/*
			Creates and persists a new position.
		*/
		public function createBlockPosition() {
			$new_position = new BlockPosition();
			$postfix = 1;
			while (!is_null($this->getBlockPositionByName($new_position->getName()))) {
				$new_position->setName("Nieuwe positie " . $postfix);
				$postfix++;
			}
			$new_id = $this->persistBlockPosition($new_position);
			$new_position->setId($new_id);
			
			return $new_position;
		}
		
		/*
			Persists the given position.
			
			@param $position The position to update
		*/
		private function persistBlockPosition($position) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "INSERT INTO block_positions (name, explanation) VALUES  ('" . $position->getName() . "', NULL)";
		
			$mysql_database->executeQuery($query);
			
			return mysql_insert_id();
		}
		
		/*
			Updates the given position.
			
			@param $position The position to update
		*/
		public function updateBlockPosition($position) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "UPDATE block_positions SET name = '" . $position->getName() . "'
					 , explanation = '" . $position->getExplanation() . "' WHERE id = " . $position->getId();
			$mysql_database->executeQuery($query);
		}
		
		/*
			Deletes the position with the given ID.
			
			@param $position_id The ID of the position
				   to update
		*/
		public function deleteBlockPosition($position) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "DELETE FROM block_positions WHERE id = " . $position->getId();
			
			$mysql_database->executeQuery($query);
		}
				
		/*
			Adds the given block to the given page.
			
			@param $block_id The ID of the block to add to the page
			@param $page The page to add the term to
		*/
		public function addBlockToPage($block_id, $page) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "INSERT INTO blocks_pages (page_id, block_id) VALUES (" . $page->getId() . ", " . $block_id . ")";

			$mysql_database->executeQuery($query);
		}
		
		/*
			Deletes the block with the given ID from the given page.
			
			@param $block_id The ID of the term to delete from the page
			@param $page The page to delete the term from
		*/
		public function deleteBlockFromPage($block_id, $page) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "DELETE FROM blocks_pages WHERE page_id = " . $page->getId() ."
			          AND block_id = " . $block_id;
			
			$mysql_database->executeQuery($query);
		}
		
		
	}
?>