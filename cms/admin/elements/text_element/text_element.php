<?php

	// No direct access
	defined('_ACCESS') or die;

	require_once CMS_ROOT . "/core/data/element.php";
	require_once CMS_ROOT . "/elements/text_element/visuals/text_element_form.php";
	require_once CMS_ROOT . "/elements/text_element/visuals/text_element_statics.php";
	require_once CMS_ROOT . "/database/mysql_connector.php";

	class TextElement extends Element {
	
		private static $TABLE_NAME = "text_elements_metadata";
			
		private $_title;
		private $_text;
			
		public function __construct() {
			// set all text element specific metadata
			$this->myMetaDataProvider = new TextElementMetaDataProvider();
		}
		
		public function setTitle($title) {
			$this->_title = $title;
		}
		
		public function getTitle() {
			//include_once CMS_ROOT . "/libraries/utilities/link_utility.php";
			$title = $this->_title;
			//if (CMS_ROOT != '') {
			//	$title = LinkUtility::createLinksInString($title, $this->getElementHolder());
			//}
			return $title;
		}
		
		public function setText($text) {
			$this->_text = $text;
		}
		
		public function getText() {
			//include_once CMS_ROOT . "/libraries/utilities/link_utility.php";
			$text = $this->_text;
			//if (CMS_ROOT != '') {
			//	// replace newlines with HTML breaks
			//	$text = nl2br($text);
			//	// replace link codes with actual links
			//	$text = LinkUtility::createLinksInString($text, $this->getElementHolder());
			//}
			return $text;
		}
		
		public function getStatics() {
			return new TextElementStatics();
		}
		
		public function getEditForm() {
			return new TextElementFormVisual($this);
		}
		
		public function initializeMetaData() {
			$this->myMetaDataProvider->getMetaData($this);
		}
		
		public function updateMetaData() {
			$this->myMetaDataProvider->updateMetaData($this);
        }
		
	}
	
	class TextElementMetaDataProvider {
		
		public function getMetaData($element) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT title, text FROM text_elements_metadata WHERE element_id = " . $element->getId();
			$result = $mysql_database->executeSelectQuery($query);
			while ($row = mysql_fetch_array($result)) {
				$element->setTitle($row['title']);
				$element->setText($row['text']);
			}
		}
		
		public function updateMetaData($element) {
			// check if the metadata exists first
			$mysql_database = MysqlConnector::getInstance(); 
			
			
			if ($this->persisted($element)) {
				$query = "UPDATE text_elements_metadata SET title = '" . $element->getTitle() . "', text = '" 
						  . $element->getText() . "' WHERE element_id = " . $element->getId();
			} else {
				$query = "INSERT INTO text_elements_metadata (title, text, element_id) VALUES 
				          ('" . $element->getTitle() . "', '" . $element->getText() . "', " . $element->getId() . ")"; 
			}
			$mysql_database->executeQuery($query);		
		}
		
		// checks if the metadata is already persisted
		private function persisted($element) {
			$mysql_database = MysqlConnector::getInstance(); 
			$query = "SELECT t.id, e.id FROM text_elements_metadata t, elements e WHERE t.element_id = " . $element->getId() . "
					  AND e.id = " . $element->getId();
			$result = $mysql_database->executeSelectQuery($query);
			while ($row = mysql_fetch_array($result)) {
				return true;
			}
			return false;
		}
		
	}
	
?>