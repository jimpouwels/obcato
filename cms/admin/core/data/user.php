<?php

	// No direct access
	defined('_ACCESS') or die;
	
	include_once CMS_ROOT . "core/data/entity.php";
	
	class User extends Entity {
	
		private $myUsername;
		private $myEmailAddress;
		private $myFirstName;
		private $myLastName;
		private $myPassword;
		private $myPrefix;
		private $myUuid;
		
		public function getUsername() {
			return $this->myUsername;
		}
		
		public function setUsername($username) {
			$this->myUsername = $username;
		}
		
		public function getPassword() {
			return $this->myPassword;
		}
		
		public function setPassword($password) {
			$this->myPassword = $password;
		}
		
		public function getEmailAddress() {
			return $this->myEmailAddress;
		}
		
		public function setEmailAddress($email_address) {
			$this->myEmailAddress = $email_address;
		}
		
		public function getFirstName() {
			return $this->myFirstName;
		}
		
		public function setFirstName($first_name) {
			$this->myFirstName = $first_name;
		}
		
		public function getLastName() {
			return $this->myLastName;
		}
		
		public function setLastName($last_name) {
			$this->myLastName = $last_name;
		}
		
		public function getPrefix() {
			return $this->myPrefix;
		}
		
		public function setPrefix($prefix) {
			$this->myPrefix = $prefix;
		}
		
		public function getUuid() {
			return $this->myUuid;
		}
		
		public function setUuid($uuid) {
			$this->myUuid = $uuid;
		}
		
		public function getFullName() {
			$full_name = $this->myFirstName;
			if (!is_null($this->myPrefix) && $this->myPrefix != '') {
				$full_name = $full_name . ' ' . $this->myPrefix;
			}
			$full_name = $full_name . ' ' . $this->myLastName;
			return $full_name;
		}
		
		public function isLoggedInUser() {
			return $this->getUsername() == $_SESSION["username"];
		}
		
		public static function constructFromRecord($record) {
			$user = new User($record['role_id']);
			$user->setId($record['id']);
			$user->setUsername($record['username']);
			$user->setEmailAddress($record['email_address']);
			$user->setFirstName($record['first_name']);
			$user->setLastName($record['last_name']);
			$user->setPrefix($record['prefix']);
			$user->setUuid($record['uuid']);
			
			return $user;
		}
	}
	
	
?>