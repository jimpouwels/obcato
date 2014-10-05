<?php
    defined("_ACCESS") or die;

    require_once CMS_ROOT . "/view/forms/form.php";

    class AuthorizationForm extends Form {

        private $_user;
        private $_authorization_dao;

        public function __construct($user, $authorization_dao) {
            $this->_user = $user;
            $this->_authorization_dao = $authorization_dao;
        }

        public function loadFields() {
            $username = $this->getFieldValue("user_username", 'Gebruikersnaam is verplicht');
            if ($this->userExists($username))
                $this->raiseError('user_username', 'Er bestaat al een gebruiker met deze gebruikersnaam');
            $first_name = $this->getMandatoryFieldValue('user_firstname', 'Voornaam is verplicht');
            $last_name = $this->getMandatoryFieldValue('user_lastname', 'Voornaam is verplicht');
            $prefix = $this->getFieldValue('user_prefix');
            $email = $this->getMandatoryEmailAddress('user_email', 'Email adres is verplicht', 'Vul een geldig email adres in');
            $password = $this->getPassword('user_new_password_first', 'user_new_password_second');

            if (!$this->hasErrors()) {
                $this->_user->setUsername($username);
                $this->_user->setFirstName($first_name);
                $this->_user->setLastName($last_name);
                $this->_user->setPrefix($prefix);
                $this->_user->setEmailAddress($email);
                if ($password)
                    $this->_user->setPassword($password);
            } else {
                throw new FormException();
            }
        }

        private function userExists($username) {
            $existing_user = $this->_authorization_dao->getUser($username);
            return !is_null($existing_user) && ($existing_user->getId() != $this->_user->getId());
        }
    }