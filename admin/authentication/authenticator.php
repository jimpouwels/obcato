<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/utilities/string_utility.php";
require_once CMS_ROOT . "/database/dao/AuthorizationDaoMysql.php";

class Authenticator {

    public static function isAuthenticated(): bool {
        if (!isset($_SESSION)) {
            session_start();
        }
        $authorization_dao = AuthorizationDaoMysql::getInstance();
        if (isset($_SESSION['last_activity'])
            && (time() - $_SESSION['last_activity'] < SESSION_TIMEOUT)
            && isset($_SESSION['username'])) {
            $user = $authorization_dao->getUser($_SESSION['username']);
            if ($user->getUuid() == $_SESSION['uuid']) {
                $_SESSION['last_activity'] = time();
                return true;
            }
        }
        return false;
    }

    public static function logIn(string $username, string $password): void {
        if (self::authenticate($username, $password)) {
            session_start();
            $authorization_dao = AuthorizationDaoMysql::getInstance();
            $user = $authorization_dao->getUser($username);
            $_SESSION['username'] = $username;
            $_SESSION['uuid'] = $user->getUuid();
            $_SESSION['last_activity'] = time();
        }
    }

    private static function authenticate(string $username, string $password): bool {
        $mysql_database = MysqlConnector::getInstance();
        $password = StringUtility::hashStringValue($password);
        $auth_query = "SELECT * FROM auth_users WHERE username = ? AND password = ?";
        $statement = $mysql_database->prepareStatement($auth_query);
        $statement->bind_param("ss", $username, $password);
        $result = $mysql_database->executeStatement($statement);
        return $result->num_rows > 0;
    }

    public static function logOut(): void {
        session_start();
        session_destroy();
        header('Location: /admin/login.php');
        exit();
    }

    public static function getCurrentUser(): User {
        $authorization_dao = AuthorizationDaoMysql::getInstance();
        return $authorization_dao->getUser($_SESSION["username"]);
    }

}
