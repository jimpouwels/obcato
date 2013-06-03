<?php

	// No direct access
	defined('_ACCESS') or die;

	include_once FRONTEND_REQUEST . "core/data/element.php";
	include_once FRONTEND_REQUEST . "libraries/system/mysql_connector.php";

	class YoutubeElement extends Element {
			
		private $myTitle;
		private $myEmbed;
		private $myMetaDataProvider;
			
		public function __construct() {
			// set all text element specific metadata
			$this->myMetaDataProvider = new YoutubeElementMetaDataProvider();
		}
		
		public function setTitle($title) {
			$this->myTitle = $title;
		}
		
		public function getTitle() {
			include_once FRONTEND_REQUEST . "libraries/utilities/link_utility.php";
			$title = $this->myTitle;
			if (FRONTEND_REQUEST != '') {
				$title = LinkUtility::createLinksInString($title, $this->getElementHolder());
			}
			return $title;
		}
		
		public function setEmbed($embed) {
			$this->myEmbed = $embed;
		}
		
		public function getEmbed() {
			return $this->myEmbed;
		}
		
		public function initializeMetaData() {
			$this->myMetaDataProvider->getMetaData($this);
		}
		
		public function updateMetaData() {
			$this->myMetaDataProvider->updateMetaData($this);
		}
		
	}
	
	class YoutubeElementMetaDataProvider {
		
		public function getMetaData($element) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT title, embed FROM youtube_elements_metadata WHERE element_id = " . $element->getId();
			$result = $mysql_database->executeSelectQuery($query);
			while ($row = mysql_fetch_array($result)) {
				$element->setTitle($row['title']);
				$element->setEmbed($row['embed']);
			}
		}
		
		public function updateMetaData($element) {
			// check if the metadata exists first
			$mysql_database = MysqlConnector::getInstance(); 
			
			
			if ($this->persisted($element)) {
				$query = "UPDATE youtube_elements_metadata SET title = '" . $element->getTitle() . "', embed = '" 
						  . $element->getEmbed() . "' WHERE element_id = " . $element->getId();
			} else {
				$query = "INSERT INTO youtube_elements_metadata (title, embed, element_id) VALUES 
				          ('" . $element->getTitle() . "', '" . $element->getEmbed() . "', " . $element->getId() . ")"; 
			}
			$mysql_database->executeQuery($query);		
		}
		
		// checks if the metadata is already persisted
		private function persisted($element) {
			$mysql_database = MysqlConnector::getInstance(); 
			$query = "SELECT t.id, e.id FROM youtube_elements_metadata t, elements e WHERE t.element_id = " . $element->getId() . "
					  AND e.id = " . $element->getId();
			$result = $mysql_database->executeSelectQuery($query);
			while ($row = mysql_fetch_array($result)) {
				return true;
			}
			return false;
		}
		
	}
	
?>