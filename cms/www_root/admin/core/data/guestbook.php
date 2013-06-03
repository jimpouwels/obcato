<?php

	// No direct access
	defined('_ACCESS') or die;

	include_once FRONTEND_REQUEST . "core/data/entity.php";
	include_once FRONTEND_REQUEST . "dao/guestbook_dao.php";
	include_once FRONTEND_REQUEST . "dao/authorization_dao.php";

	class GuestBook extends Entity {
	
		private $myTitle;
		private $myClosed;
		private $myAutoAcknowledge;
		private $myCreatedAt;
		private $myCreatedById;
		
		public function getTitle() {
			return $this->myTitle;
		}
		
		public function setTitle($title) {
			$this->myTitle = $title;
		}
		
		public function isClosed() {
			return $this->myClosed;
		}
		
		public function setClosed($closed) {
			$this->myClosed = $closed;
		}
		
		public function isAutoAcknowledge() {
			return $this->myAutoAcknowledge;
		}
		
		public function setAutoAcknowledge($auto_acknowledge) {
			$this->myAutoAcknowledge = $auto_acknowledge;
		}
		
		public function getCreatedById() {
			return $this->myCreatedById;
		}
		
		public function setCreatedById($created_by_id) {
			$this->myCreatedById = $created_by_id;
		}
		
		public function getCreatedAt() {
			return $this->myCreatedAt;
		}
		
		public function setCreatedAt($created_at) {
			$this->myCreatedAt = $created_at;
		}
		
		public function getCreatedBy() {
			$authorization_dao = AuthorizationDao::getInstance();
			return $authorization_dao->getUserById($this->myCreatedById);
		}
		
		public function getMessages() {
			$guestbook_dao = GuestBookDao::getInstance();
			return $guestbook_dao->getMessagesByGuestBook($this->getId());
		}
		
		public function addMessage($message) {
			$guestbook_dao = GuestBookDao::getInstance();
			$guestbook_dao->persistMessage($message, $this->getId());
		}
		
		public function getMessageCount() {
			$guestbook_dao = GuestBookDao::getInstance();
			return $guestbook_dao->getMessageCount($this->getId());
		}
		
		public static function constructFromRecord($record) {
			$guestbook = new GuestBook();
			$guestbook->setId($record['id']);
			$guestbook->setTitle($record['title']);
			$guestbook->setCreatedAt($record['created_at']);
			$guestbook->setCreatedById($record['created_by']);
			$guestbook->setClosed($record['closed']);
			$guestbook->setAutoAcknowledge($record['auto_acknowledge']);
			
			return $guestbook;
		}
		
	}
	
?>