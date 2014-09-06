<?php

	// No direct access
	defined('_ACCESS') or die;
	
	include_once CMS_ROOT . "core/data/entity.php";
	include_once CMS_ROOT . "libraries/utilities/link_utility.php";

	class Link extends Entity {
		
		const INTERNAL = "INTERNAL";
		const EXTERNAL = "EXTERNAL";
	
		private $myTitle;
		private $myUrl;
		private $myType;
		private $myCode;
		private $myTargetElementHolderId;
		private $myParentElementHolderId;
		
		public function getTitle() {
			return $this->myTitle;
		}
		
		public function setTitle($title) {
			$this->myTitle = $title;
		}
		
		public function getUrl() {
			$url = NULL;
			if ($this->myType == Link::EXTERNAL) {
				$url = LinkUtil::createUrlFromLink($this);
			} else {
				$url = $this->myUrl;
			}
			return $url;
		}
		
		public function getTargetAddress() {
			return $this->myUrl;
		}
		
		public function setTargetAddress($url) {
			$this->myUrl = $url;
		}
		
		public function getType() {
			return $this->myType;
		}
		
		public function setType($type) {
			$this->myType = $type;
		}
		
		public function getTargetElementHolder() {
			$element_holder = NULL;
			if (!is_null($this->myTargetElementHolderId) && $this->myTargetElementHolderId != '') {
				$element_holder = $this->getElementHolder($this->myTargetElementHolderId);
			}
			return $element_holder;
		}
		
		public function getParentElementHolder() {
			$element_holder = NULL;
			if (!is_null($this->myParentElementHolderId) && $this->myParentElementHolderId != '') {
				$element_holder = $this->getElementHolder($this->myParentElementHolderId);
			}
			return $element_holder;
		}
		
		public function getTargetElementHolderId() {
			return $this->myTargetElementHolderId;
		}
		
		public function setTargetElementHolderId($target_element_holder_id) {
			$this->myTargetElementHolderId = $target_element_holder_id;
		}
		
		public function getParentElementHolderId() {
			return $this->myParentElementHolderId;
		}
		
		public function setParentElementHolderId($parent_element_holder_id) {
			$this->myParentElementHolderId = $parent_element_holder_id;
		}
		
		public function getCode() {
			return $this->myCode;
		}
		
		public function setCode($code) {
			$this->myCode = $code;
		}
		
		private function getElementHolder($element_holder_id) {
			include_once CMS_ROOT . 'database/dao/element_holder_dao.php';
			$element_holder_dao = ElementHolderDao::getInstance();
			$element_holder = $element_holder_dao->getElementHolder($element_holder_id);
			return $element_holder;
		}
		
		public function persist() {
		}
		
		public function update() {
		}
		
		public function delete() {
		}
		
		public static function constructFromRecord($record) {
			$link = new Link();
			$link->setId($record['id']);
			$link->setTitle($record['title']);
			$link->setTargetAddress($record['target_address']);
			$link->setType($record['type']);
			$link->setCode($record['code']);
			$link->setParentElementHolderId($record['parent_element_holder']);
			$link->setTargetElementHolderId($record['target_element_holder']);
			
			return $link;
		}
	}
	
?>