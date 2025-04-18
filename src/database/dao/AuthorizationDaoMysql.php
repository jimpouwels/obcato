<?php

namespace Obcato\Core\database\dao;

use Obcato\Core\database\MysqlConnector;
use Obcato\Core\modules\authorization\model\User;
use Obcato\Core\utilities\StringUtility;

class AuthorizationDaoMysql implements AuthorizationDao {

    private static ?AuthorizationDaoMysql $instance = null;
    private MysqlConnector $mysqlConnector;

    private function __construct() {
        $this->mysqlConnector = MysqlConnector::getInstance();
    }

    public static function getInstance(): AuthorizationDaoMysql {
        if (!self::$instance) {
            self::$instance = new AuthorizationDaoMysql();
        }
        return self::$instance;
    }

    public function getUser(string $username): ?User {
        $query = "SELECT * FROM auth_users WHERE username = ?";
        $statement = $this->mysqlConnector->prepareStatement($query);
        $statement->bind_param("s", $username);
        $result = $this->mysqlConnector->executeStatement($statement);
        while ($row = $result->fetch_assoc()) {
            return User::constructFromRecord($row);
        }
        return null;
    }

    public function getUserById(int $id): ?User {
        $statement = $this->mysqlConnector->prepareStatement("SELECT * FROM auth_users WHERE id = ?");
        $statement->bind_param("i", $id);
        $result = $this->mysqlConnector->executeStatement($statement);
        while ($row = $result->fetch_assoc()) {
            return User::constructFromRecord($row);
        }
        return null;
    }

    public function getAllUsers(): array {
        $result = $this->mysqlConnector->executeQuery("SELECT * FROM auth_users ORDER BY first_name, last_name");
        $users = array();
        while ($row = $result->fetch_assoc()) {
            $users[] = User::constructFromRecord($row);
        }
        return $users;
    }

    public function updateUser(User $user): void {
        $query = "UPDATE auth_users SET username = '" . $user->getUsername() . "', 
                                  first_name = '" . $user->getFirstName() . "', 
                                  last_name = '" . $user->getLastName() . "',
                                  email_address = '" . $user->getEmailAddress() . "',
                                  prefix = '" . $user->getPrefix() . "',
                                  uuid = '" . $user->getUuid() . "'";
        if (!is_null($user->getPassword()) && $user->getPassword() != '') {
            $query = $query . ", password = '" . StringUtility::hashStringValue($user->getPassword()) . "'";
        }
        $query = $query . " WHERE id = ?";
        $statement = $this->mysqlConnector->prepareStatement($query);
        $userId = $user->getId();
        $statement->bind_param("i", $userId);
        $this->mysqlConnector->executeStatement($statement);
    }

    public function deleteUser(int $userId): void {
        $statement = $this->mysqlConnector->prepareStatement("DELETE FROM auth_users WHERE id = ?");
        $statement->bind_param("i", $userId);
        $this->mysqlConnector->executeStatement($statement);
    }

    public function createUser(): User {
        $newUser = new User();
        $newUser->setUsername("user" . $newUser->getId());
        $newUser->setFirstName('Nieuwe');
        $newUser->setLastName('Gebruiker');
        $newUser->setId($this->persistUser($newUser));
        return $newUser;
    }

    private function persistUser(User $user): string {
        $query = "INSERT INTO auth_users (username, password, email_address, first_name, last_name, prefix,
                      created_at, uuid) VALUES ('" . $user->getUsername() . "', '" . StringUtility::hashStringValue('123456') .
            "', NULL, '" . $user->getFirstName() . "', '" . $user->getLastName() . "', NULL, now(), '" . $user->getUuid() . "')";
        $this->mysqlConnector->executeQuery($query);
        return $this->mysqlConnector->getInsertId();
    }

}
