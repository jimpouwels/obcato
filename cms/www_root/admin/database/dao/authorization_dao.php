<?php

	// No direct access
	defined('_ACCESS') or die;

	include_once FRONTEND_REQUEST . "core/data/user.php";
	include_once FRONTEND_REQUEST . "libraries/utilities/string_utility.php";
	include_once FRONTEND_REQUEST . "database/mysql_connector.php";

	class AuthorizationDao {
	
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
			Creates (if not exists) and returns an instance.
		*/
		public static function getInstance() {
			if (!self::$instance) {
				self::$instance = new AuthorizationDao();
			}
			return self::$instance;
		}
		
		/*
			Returns the user with the given user name.
			
			@param $username The username to find the user for
		*/
		public function getUser($username) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			// prevent SQL injection
			$username = mysql_real_escape_string($username);
			$query = "SELECT * FROM auth_users WHERE username = '" . $username . "';";
			$result = $mysql_database->executeSelectQuery($query);
			$user = NULL;
			while ($row = mysql_fetch_array($result)) {
				$user = User::constructFromRecord($row);
				
				// there should be one result
				break;
			}
			
			return $user;
		}
		
		/*
			Returns the user with the given ID.
			
			@param $id The ID to find the user for
		*/
		public function getUserById($id) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			// prevent SQL injection
			$query = "SELECT * FROM auth_users WHERE id = '" . $id . "';";
			$result = $mysql_database->executeSelectQuery($query);
			$user = NULL;
			while ($row = mysql_fetch_array($result)) {
				$user = User::constructFromRecord($row);
				
				// there should be one result
				break;
			}
			
			return $user;
		}
		
		/*
			Returns all users.
		*/
		public function getAllUsers() {
			$mysql_database = MysqlConnector::getInstance(); 
			
			// prevent SQL injection
			$query = "SELECT * FROM auth_users ORDER BY first_name, last_name";
			$result = $mysql_database->executeSelectQuery($query);
			$users = array();
			while ($row = mysql_fetch_array($result)) {
				$user = User::constructFromRecord($row);
				
				array_push($users, $user);
			}
			
			return $users;
		}
		
		/*
			Updates the given user.
			
			@param $user The user to update
		*/
		public function updateUser($user) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "UPDATE auth_users SET username = '" . $user->getUsername() . "', 
								  first_name = '" . $user->getFirstName() . "', 
								  last_name = '" . $user->getLastName() . "',
								  email_address = '" . $user->getEmailAddress() . "',
								  prefix = '" . $user->getPrefix() . "',
								  uuid = '" . $user->getUuid() . "'";
			if (!is_null($user->getPassword()) && $user->getPassword() != '') {
				$query = $query . ", password = '" . StringUtility::hashStringValue($user->getPassword()) . "'";
			}
								  
			$query = $query . " WHERE id = " . $user->getId();
			$mysql_database->executeQuery($query);
		}
		
		/*
			Deletes the given user.
			
			@param $user The user to delete
		*/
		public function deleteUser($user_id) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "DELETE FROM auth_users WHERE id = " . $user_id;
			$mysql_database->executeQuery($query);
		}
		
		/*
			Creates a new user.
		*/
		public function createUser() {
			$mysql_database = MysqlConnector::getInstance(); 
			$new_user = new User();
			$new_user->setUsername('user' . $new_user->getId());
			$new_user->setFirstName('Nieuwe');
			$new_user->setLastName('Gebruiker');
			
			$new_id = $this->persistUser($new_user);
			$new_user->setId($new_id);
			
			return $new_user;
		}
		
		/*
			Persists the given user.
			
			@param $user The user to persist
		*/
		private function persistUser($user) {
			include_once "libraries/utilities/string_utility.php";
			$mysql_database = MysqlConnector::getInstance(); 
			
			
			$query = "INSERT INTO auth_users (username, password, email_address, first_name, last_name, prefix,
					  created_at, uuid) VALUES ('" . $user->getUsername() . "', '" . StringUtility::hashStringValue('123456') . 
					  "', NULL, '" . $user->getFirstName() . "', '" . $user->getLastName() . "', NULL, now(), '" . $user->getUuid() . "')";
			$mysql_database->executeQuery($query);
			
			return mysql_insert_id();
		}
		
	}
?>