<?php
	// No direct access
	defined('_ACCESS') or die;
	
	include_once "libraries/utilities/string_utility.php";
	include_once "libraries/system/mysql_connector.php";
	include_once "dao/authorization_dao.php";

	class Session {
	
		// checks if the user is authenticated, if not redirect
		// login page
		public function isAuthenticated() {
			include_once "libraries/system/constants.php";
			
			$authenticated = false;
			session_start();
			$authorization_dao = AuthorizationDao::getInstance();
			if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] < SESSION_TIMEOUT)) {
				if (isset($_SESSION['username'])) {
					$user = $authorization_dao->getUser($_SESSION['username']);
					if ($user->getUuid() == $_SESSION['uuid']) {
						$authenticated = true;
						$_SESSION['last_activity'] = time();
					}
				}
			}
			
			return $authenticated;
		}
		
		// tries to authenticate the user and add the user info to
		// the session
		public function logIn($username, $password) {
			$authenticated = false;
			if (self::authenticate($username, $password)) {
				session_start();
				$authorization_dao = AuthorizationDao::getInstance();
				$user = $authorization_dao->getUser($username);
				$_SESSION['username'] = $username;
				$_SESSION['uuid'] = $user->getUuid();
				$_SESSION['last_activity'] = time();
				$authenticated = true;
			}
			return $authenticated;
		}
		
		// logs out the current user
		public function logOut($username) {
			session_start();
			session_destroy();
			header('Location: /admin/login.php');
			exit();
		}
		
		// Authenticates the given username with the given password
		private function authenticate($username, $password) {
			// open DB connection
			$mysql_database = MysqlConnector::getInstance(); 
			
			
			// prevent SQL injection
			$username = mysql_real_escape_string($username);
			// hash the password
			$password = StringUtility::hashStringValue($password);
			
			// execute the query
			$auth_query = "SELECT * FROM auth_users WHERE username = '" . $username . "' AND password = '" . $password . "';";
			
			$result = $mysql_database->executeSelectQuery($auth_query);
			
			$authenticated = false;
			if (mysql_num_rows($result) > 0) {
				$authenticated = true;
			}
			
			return $authenticated;
		}
		
	}
	
?>