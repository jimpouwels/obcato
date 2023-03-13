<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "utilities/string_utility.php";
    require_once CMS_ROOT . "database/dao/authorization_dao.php";

    class Authenticator {

        public static function isAuthenticated() {
            if(!isset($_SESSION)) {
                session_start();
            }
            $authorization_dao = AuthorizationDao::getInstance();
            if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] < SESSION_TIMEOUT)) {
                if (isset($_SESSION['username'])) {
                    $user = $authorization_dao->getUser($_SESSION['username']);
                    if ($user->getUuid() == $_SESSION['uuid']) {
                        $_SESSION['last_activity'] = time();
                        return true;
                    }
                }
            }
        }

        public static function logIn($username, $password) {
            if (self::authenticate($username, $password)) {
                session_start();
                $authorization_dao = AuthorizationDao::getInstance();
                $user = $authorization_dao->getUser($username);
                $_SESSION['username'] = $username;
                $_SESSION['uuid'] = $user->getUuid();
                $_SESSION['last_activity'] = time();
            }
        }

        public static function logOut() {
            session_start();
            session_destroy();
            header('Location: /admin/login.php');
            exit();
        }

        public static function getCurrentUser() {
            $authorization_dao = AuthorizationDao::getInstance();
            return $authorization_dao->getUser($_SESSION["username"]);
        }

        private static function authenticate($username, $password) {
            $mysql_database = MysqlConnector::getInstance();
            $password = StringUtility::hashStringValue($password);
            $auth_query = "SELECT * FROM auth_users WHERE username = ? AND password = ?";
            $statement = $mysql_database->prepareStatement($auth_query);
            $statement->bind_param("ss", $username, $password);
            $result = $mysql_database->executeStatement($statement);
            return $result->num_rows > 0;
        }

    }

?>
