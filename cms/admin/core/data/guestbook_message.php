<?php

	// No direct access
	defined('_ACCESS') or die;

	include_once FRONTEND_REQUEST . "core/data/entity.php";
	include_once FRONTEND_REQUEST . "database/dao/guestbook_dao.php";

	class GuestBookMessage extends Entity {
	
		private $myMessage;
		private $myAuthor;
		private $myEmailAddress;
		private $myPostedAt;
		private $myAcknowledged;
		
		public function getMessage() {
			return $this->myMessage;
		}
		
		public function setMessage($message) {
			$this->myMessage = $message;
		}
		
		public function getAuthor() {
			return $this->myAuthor;
		}
		
		public function setAuthor($author) {
			$this->myAuthor = $author;
		}
		
		public function getEmailAddress() {
			return $this->myEmailAddress;
		}
		
		public function setEmailAddress($email_address) {
			$this->myEmailAddress = $email_address;
		}
		
		public function getPostedAt() {
			return $this->myPostedAt;
		}
		
		public function setPostedAt($posted_at) {
			$this->myPostedAt = $posted_at;
		}
		
		public function isAcknowledged() {
			return $this->myAcknowledged;
		}
		
		public function setAcknowledged($acknowledged) {
			$this->myAcknowledged = $acknowledged;
		}
		
		public static function constructFromRecord($record) {
			$message = new GuestbookMessage();
			$message->setId($record['id']);
			$message->setMessage($record['message']);
			$message->setAuthor($record['author']);
			$message->setEmailAddress($record['email_address']);
			$message->setPostedAt($record['posted_at']);
			$message->setAcknowledged($record['acknowledged']);
			
			return $message;
		}
		
	}
	
?>