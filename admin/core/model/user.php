<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/entity.php";
    
    class User extends Entity {
    
        private $_username;
        private $_email_address;
        private $_first_name;
        private $_prefix;
        private $_last_name;
        private $_password;
        private $_uuid;
        
        public function getUsername() {
            return $this->_username;
        }
        
        public function setUsername($username) {
            $this->_username = $username;
        }
        
        public function getPassword() {
            return $this->_password;
        }
        
        public function setPassword($password) {
            $this->_password = $password;
        }
        
        public function getEmailAddress() {
            return $this->_email_address;
        }
        
        public function setEmailAddress($email_address) {
            $this->_email_address = $email_address;
        }
        
        public function getFirstName() {
            return $this->_first_name;
        }
        
        public function setFirstName($first_name) {
            $this->_first_name = $first_name;
        }
        
        public function getLastName() {
            return $this->_last_name;
        }
        
        public function setLastName($last_name) {
            $this->_last_name = $last_name;
        }
        
        public function getPrefix() {
            return $this->_prefix;
        }
        
        public function setPrefix($prefix) {
            $this->_prefix = $prefix;
        }
        
        public function getUuid() {
            return $this->_uuid;
        }
        
        public function setUuid($uuid) {
            $this->_uuid = $uuid;
        }
        
        public function getFullName() {
            $full_name = $this->getFirstName();
            if (!is_null($this->getPrefix()) && $this->getPrefix() != '') {
                $full_name = $full_name . ' ' . $this->getPrefix();
            }
            $full_name = $full_name . ' ' . $this->getLastName();
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