<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/user.php";
    require_once CMS_ROOT . "utilities/string_utility.php";
    require_once CMS_ROOT . "database/mysql_connector.php";

    class AuthorizationDao {

        private $_mysql_connector;

        private static $instance;

        private function __construct() {
            $this->_mysql_connector = MysqlConnector::getInstance();
        }

        public static function getInstance() {
            if (!self::$instance)
                self::$instance = new AuthorizationDao();
            return self::$instance;
        }

        public function getUser($username) {
            $query = "SELECT * FROM auth_users WHERE username = ?";
            $statement = $this->_mysql_connector->prepareStatement($query);
            $statement->bind_param("s", $username);
            $result = $this->_mysql_connector->executeStatement($statement);
            while ($row = $result->fetch_assoc())
                return User::constructFromRecord($row);
        }

        public function getUserById($id) {
            $statement = $this->_mysql_connector->prepareStatement("SELECT * FROM auth_users WHERE id = ?");
            $statement->bind_param("i", $id);
            $result = $this->_mysql_connector->executeStatement($statement);
            while ($row = $result->fetch_assoc())
                return User::constructFromRecord($row);
        }

        public function getAllUsers() {
            $result = $this->_mysql_connector->executeQuery("SELECT * FROM auth_users ORDER BY first_name, last_name");
            $users = array();
            while ($row = $result->fetch_assoc())
                $users[] = User::constructFromRecord($row);
            return $users;
        }

        public function updateUser($user) {
            $query = "UPDATE auth_users SET username = '" . $user->getUsername() . "', 
                                  first_name = '" . $user->getFirstName() . "', 
                                  last_name = '" . $user->getLastName() . "',
                                  email_address = '" . $user->getEmailAddress() . "',
                                  prefix = '" . $user->getPrefix() . "',
                                  uuid = '" . $user->getUuid() . "'";
            if (!is_null($user->getPassword()) && $user->getPassword() != '')
                $query = $query . ", password = '" . StringUtility::hashStringValue($user->getPassword()) . "'";
            $query = $query . " WHERE id = ?";
            $statement = $this->_mysql_connector->prepareStatement($query);
            $statement->bind_param("i", $user->getId());
            $this->_mysql_connector->executeStatement($statement);
        }

        public function deleteUser($user_id) {
            $statement = $this->_mysql_connector->prepareStatement("DELETE FROM auth_users WHERE id = ?");
            $statement->bind_param("i", $user_id);
            $this->_mysql_connector->executeStatement($statement);
        }

        public function createUser() {
            $new_user = new User();
            $new_user->setUsername("user" . $new_user->getId());
            $new_user->setFirstName('Nieuwe');
            $new_user->setLastName('Gebruiker');
            $new_id = $this->persistUser($new_user);
            $new_user->setId($new_id);
            return $new_user;
        }

        private function persistUser($user) {
            $query = "INSERT INTO auth_users (username, password, email_address, first_name, last_name, prefix,
                      created_at, uuid) VALUES ('" . $user->getUsername() . "', '" . StringUtility::hashStringValue('123456') . 
                      "', NULL, '" . $user->getFirstName() . "', '" . $user->getLastName() . "', NULL, now(), '" . $user->getUuid() . "')";
            $this->_mysql_connector->executeQuery($query);
            return $this->_mysql_connector->getInsertId();
        }
        
    }
?>