<?php

defined("_ACCESS") or die;

require_once CMS_ROOT . "/request_handlers/http_request_handler.php";
require_once CMS_ROOT . "/modules/authorization/AuthorizationForm.php";
require_once CMS_ROOT . "/database/dao/AuthorizationDaoMysql.php";
require_once CMS_ROOT . "/utilities/password_utility.php";

class AuthorizationRequestHandler extends HttpRequestHandler {

    private AuthorizationDao $_authorization_dao;
    private User $_current_user;

    public function __construct() {
        $this->_authorization_dao = AuthorizationDaoMysql::getInstance();
    }

    public function handleGet(): void {
        $this->_current_user = $this->getCurrentUserFromGetRequest();
    }

    public function handlePost(): void {
        $this->_current_user = $this->getCurrentUserFromPostRequest();
        if ($this->isUpdateUserAction()) {
            $this->updateUser();
        }
        if ($this->isAddUserAction()) {
            $this->addUser();
        }
        if ($this->isDeleteUserAction()) {
            $this->deleteUser();
        }
    }

    public function getCurrentUser(): ?User {
        return $this->_current_user;
    }

    private function addUser(): void {
        $new_user = $this->_authorization_dao->createUser();
        $password = PasswordUtility::generatePassword();
        $new_user->setUuid(uniqid());
        $new_user->setPassword($password);
        $this->_authorization_dao->updateUser($new_user);
        $this->sendSuccessMessage("Gebruiker aangemaakt, met wachtwoord: " . $password);
        $this->redirectTo($this->getBackendBaseUrl() . "&user=" . $new_user->getId());
        exit();
    }

    private function deleteUser(): void {
        $this->_authorization_dao->deleteUser($this->_current_user->getId());
        $this->sendSuccessMessage("Gebruiker succesvol verwijderd");
        $this->redirectTo($this->getBackendBaseUrl());
    }

    private function updateUser(): void {
        $authorization_form = new AuthorizationForm($this->_current_user, $this->_authorization_dao);
        try {
            $authorization_form->loadFields();
            $this->_authorization_dao->updateUser($this->_current_user);
            $this->sendSuccessMessage("Gebruiker succesvol opgeslagen");
        } catch (FormException $e) {
            $this->sendErrorMessage("Gebruiker niet opgeslagen, verwerk de fouten");
        }
    }

    private function getCurrentUserFromGetRequest(): User {
        if (isset($_GET["user"])) {
            return $this->getUserFromDatabase($_GET["user"]);
        } else {
            return $this->_authorization_dao->getUser($_SESSION["username"]);
        }
    }

    private function getCurrentUserFromPostRequest(): User {
        return $this->getUserFromDatabase($_POST["user_id"]);
    }

    private function getUserFromDatabase($user_id): User {
        return $this->_authorization_dao->getUserById($user_id);
    }

    private function isUpdateUserAction(): bool {
        return isset($_POST["action"]) && $_POST["action"] == "update_user";
    }

    private function isAddUserAction(): bool {
        return isset($_POST["action"]) && $_POST["action"] == "add_user";
    }

    private function isDeleteUserAction(): bool {
        return isset($_POST["action"]) && $_POST["action"] == "delete_user";
    }

}

?>