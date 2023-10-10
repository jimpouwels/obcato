<?php
require_once CMS_ROOT . "/core/form/Form.php";

class AuthorizationForm extends Form {

    private User $user;
    private AuthorizationDao $authorizationDao;

    public function __construct(User $user, AuthorizationDaoMysql $authorizationDao) {
        $this->user = $user;
        $this->authorizationDao = $authorizationDao;
    }

    public function loadFields(): void {
        $username = $this->getMandatoryFieldValue("user_username");
        if ($this->userExists($username)) {
            $this->raiseError('user_username', 'Er bestaat al een gebruiker met deze gebruikersnaam');
        }
        $firstname = $this->getMandatoryFieldValue('user_firstname');
        $lastname = $this->getMandatoryFieldValue('user_lastname');
        $prefix = $this->getFieldValue('user_prefix');
        $email = $this->getMandatoryEmailAddress('user_email');
        $password = $this->getPassword('user_new_password_first', 'user_new_password_second');

        if (!$this->hasErrors()) {
            $this->user->setUsername($username);
            $this->user->setFirstName($firstname);
            $this->user->setLastName($lastname);
            $this->user->setPrefix($prefix);
            $this->user->setEmailAddress($email);
            if ($password)
                $this->user->setPassword($password);
        } else {
            throw new FormException();
        }
    }

    private function userExists(string $username): bool {
        $existingUser = $this->authorizationDao->getUser($username);
        return !is_null($existingUser) && ($existingUser->getId() != $this->user->getId());
    }
}