<?php

	// No direct access
	defined('_ACCESS') or die;

	require_once CMS_ROOT . "/core/data/element.php";
	require_once CMS_ROOT . "/database/mysql_connector.php";
	require_once CMS_ROOT . "/database/dao/guestbook_dao.php";
	require_once CMS_ROOT . "/elements/guestbook_element/visuals/guestbook_element_statics.php";
	require_once CMS_ROOT . "/elements/guestbook_element/visuals/guestbook_element_form.php";
    require_once CMS_ROOT . "/frontend/guestbook_element_visual.php";

	class GuestBookElement extends Element {
	
		private static $TABLE_NAME = "guestbook_elements_metadata";
			
		private $_guestbook_id;
			
		public function __construct() {
			// set all guestbook element specific metadata
			$this->myMetaDataProvider = new GuestBookElementMetaDataProvider();
		}
		
		public function setGuestBookId($guestbook_id) {
			$this->_guestbook_id = $guestbook_id;
		}
		
		public function getGuestBookId() {
			return $this->_guestbook_id;
		}
		
		public function getGuestBook() {
			$guestbook_dao = GuestBookDao::getInstance();
			$guestbook = NULL;
			if (!is_null($this->_guestbook_id) && $this->_guestbook_id != '') {
				$guestbook = $guestbook_dao->getGuestBook($this->_guestbook_id);
			}
			return $guestbook;
		}
		
		public function getStatics() {
			return new GuestBookElementStatics();
		}
		
		public function getBackendVisual() {
			return new GuestBookElementForm($this);
		}

        public function getFrontendVisual() {
            return null;
        }
		
		public function initializeMetaData() {
			$this->myMetaDataProvider->getMetaData($this);
		}
		
		public function updateMetaData() {
			$this->myMetaDataProvider->updateMetaData($this);
		}
		
		public function persist() {
			parent::persist();
		}
		
		public function update() {
			parent::update();
		}
		
		public function delete() {
			parent::delete();
		}
		
	}
	
	class GuestBookElementMetaDataProvider {
		
		public function getMetaData($element) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT guestbook_id FROM guestbook_elements_metadata WHERE element_id = " . $element->getId();
			$result = $mysql_database->executeSelectQuery($query);
			while ($row = mysql_fetch_array($result)) {
				$element->setGuestBookId($row['guestbook_id']);
			}
		}
		
		public function updateMetaData($element) {
			// check if the metadata exists first
			$mysql_database = MysqlConnector::getInstance(); 
			
			
			if ($this->persisted($element)) {
				if (!is_null($element->getGuestBookId()) && $element->getGuestBookId() != '') {
					$query = "UPDATE guestbook_elements_metadata SET guestbook_id = " . $element->getGuestBookId() 
							 . " WHERE element_id = " . $element->getId();
				} else {
					return;
				}
			} else {
				$query = "INSERT INTO guestbook_elements_metadata (guestbook_id, element_id) VALUES (NULL, " . $element->getId() . ")"; 
			}
			$mysql_database->executeQuery($query);		
		}
		
		// checks if the metadata is already persisted
		private function persisted($element) {
			$mysql_database = MysqlConnector::getInstance(); 
			$query = "SELECT t.id, e.id FROM guestbook_elements_metadata t, elements e WHERE t.element_id = " . $element->getId() . "
					  AND e.id = " . $element->getId();
			$result = $mysql_database->executeSelectQuery($query);
			while ($row = mysql_fetch_array($result)) {
				return true;
			}
			return false;
		}
	}	
?>