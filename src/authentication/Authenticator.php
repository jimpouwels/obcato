<?php

namespace Obcato\Core\authentication;

use JetBrains\PhpStorm\NoReturn;
use Obcato\Core\database\dao\AuthorizationDaoMysql;
use Obcato\Core\database\MysqlConnector;
use Obcato\Core\modules\authorization\model\User;
use Obcato\Core\utilities\StringUtility;
use const Obcato\Core\SESSION_TIMEOUT;

class Authenticator {

    public static function isAuthenticated(): void {
        if (!isset($_SESSION)) {
            session_start();
        }
        $authorizationDao = AuthorizationDaoMysql::getInstance();
        if (isset($_SESSION['last_activity'])
            && (time() - $_SESSION['last_activity'] < SESSION_TIMEOUT)
            && isset($_SESSION['username'])) {
            $user = $authorizationDao->getUser($_SESSION['username']);
            if ($user->getUuid() == $_SESSION['uuid']) {
                $_SESSION['last_activity'] = time();
                return;
            }
        }
        self::redirectToLoginPage();
    }

    public static function logIn(string $username, string $password): bool {
        if (self::authenticate($username, $password)) {
            session_start();
            $authorizationDao = AuthorizationDaoMysql::getInstance();
            $user = $authorizationDao->getUser($username);
            $_SESSION['username'] = $username;
            $_SESSION['uuid'] = $user->getUuid();
            $_SESSION['last_activity'] = time();
            return true;
        }
        return false;
    }

    public static function logOut(): void {
        session_start();
        session_destroy();
        header('Location: /admin/login');
        exit();
    }

    public static function getCurrentUser(): User {
        $authorizationDao = AuthorizationDaoMysql::getInstance();
        return $authorizationDao->getUser($_SESSION["username"]);
    }

    private static function authenticate(string $username, string $password): bool {
        $mysqlConnector = MysqlConnector::getInstance();
        $password = StringUtility::hashStringValue($password);
        $statement = $mysqlConnector->prepareStatement("SELECT * FROM auth_users WHERE username = ? AND password = ?");
        $statement->bind_param("ss", $username, $password);
        $result = $mysqlConnector->executeStatement($statement);

        while ($result->fetch_assoc()) {
            return true;
        }
        return false;
    }

    #[NoReturn]
    private static function redirectToLoginPage(): void {
        session_destroy();
        $redirectTarget = '/admin/login';
        $uri = urlencode($_SERVER['REQUEST_URI']);
        if (str_contains($uri, 'module_id')) {
            $redirectTarget .= "?orgUrl={$uri}";
        }
        header("Location: {$redirectTarget}");
        exit();
    }

}
