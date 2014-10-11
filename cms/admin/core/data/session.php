<?php
	
	defined('_ACCESS') or die;
	
	require_once CMS_ROOT . "libraries/utilities/string_utility.php";
	require_once CMS_ROOT . "database/dao/authorization_dao.php";

	class Session {
	
		public function isAuthenticated() {
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

		public function logOut($username) {
			session_start();
			session_destroy();
			header('Location: /admin/login.php');
			exit();
		}
		
		// Authenticates the given username with the given password
		private function authenticate($username, $password) {
			$mysql_database = MysqlConnector::getInstance();
			// hash the password
			$password = StringUtility::hashStringValue($password);
			
			// execute the query
			$auth_query = "SELECT * FROM auth_users WHERE username = ? AND password = ?";
            $statement = $mysql_database->prepareStatement($auth_query);
            $statement->bind_param("ss", $username, $password);
            $result = $mysql_database->executeStatement($statement);
			
			$authenticated = false;
			if ($result->num_rows > 0) {
				$authenticated = true;
			}
            $statement->close();
			return $authenticated;
		}
		
	}
	
?>