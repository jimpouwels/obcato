<?php

	// No direct access
	defined('_ACCESS') or die;

	include_once FRONTEND_REQUEST . "core/data/entity.php";

	class ListItem extends Entity {
			
		private $myText;
		private $myIndent;
		private $myElementId;
		
		public function setText($text) {
			$this->myText = $text;
		}
		
		public function getText() {
			include_once FRONTEND_REQUEST . "libraries/utilities/link_utility.php";
			$text = $this->myText;
			if (FRONTEND_REQUEST != '') {
				$text = LinkUtility::createLinksInString($text, $this->getElement()->getElementHolder());
			}
			return $text;
		}
		
		public function setIndent($indent) {
			$this->myIndent = $indent;
		}
		
		public function getIndent() {
			return $this->myIndent;
		}
		
		public function getElementId() {
			return $this->myElementId;
		}
		
		public function setElementId($element_id) {
			$this->myElementId = $element_id;
		}
		
		public function getElement() {
			include_once FRONTEND_REQUEST . "/dao/element_dao.php";
			$element_dao = ElementDao::getInstance();
			return $element_dao->getElement($this->myElementId);
		}
		
	}
?>