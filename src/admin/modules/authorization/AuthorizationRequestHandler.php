<?php

namespace Obcato\Core\admin\modules\authorization;

use Obcato\Core\admin\core\form\FormException;
use Obcato\Core\admin\database\dao\AuthorizationDao;
use Obcato\Core\admin\database\dao\AuthorizationDaoMysql;
use Obcato\Core\admin\modules\authorization\model\User;
use Obcato\Core\admin\request_handlers\HttpRequestHandler;
use Obcato\Core\PasswordUtility;

class AuthorizationRequestHandler extends HttpRequestHandler {

    private AuthorizationDao $authorizationDao;
    private User $currentUser;

    public function __construct() {
        $this->authorizationDao = AuthorizationDaoMysql::getInstance();
    }

    public function handleGet(): void {
        $this->currentUser = $this->getCurrentUserFromGetRequest();
    }

    public function handlePost(): void {
        $this->currentUser = $this->getCurrentUserFromPostRequest();
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
        return $this->currentUser;
    }

    private function addUser(): void {
        $newUser = $this->authorizationDao->createUser();
        $password = PasswordUtility::generatePassword();
        $newUser->setUuid(uniqid());
        $newUser->setPassword($password);
        $this->authorizationDao->updateUser($newUser);
        $this->sendSuccessMessage($this->getTextResource("authorization_user_created_with_password_message") . ": " . $password);
        $this->redirectTo($this->getBackendBaseUrl() . "&user=" . $newUser->getId());
        exit();
    }

    private function deleteUser(): void {
        $this->authorizationDao->deleteUser($this->currentUser->getId());
        $this->sendSuccessMessage($this->getTextResource("authorization_user_created_message"));
        $this->redirectTo($this->getBackendBaseUrl());
    }

    private function updateUser(): void {
        $authorizationForm = new AuthorizationForm($this->currentUser, $this->authorizationDao);
        try {
            $authorizationForm->loadFields();
            $this->authorizationDao->updateUser($this->currentUser);
            $this->sendSuccessMessage($this->getTextResource("authorization_user_saved_message"));
        } catch (FormException) {
            $this->sendErrorMessage($this->getTextResource("authorization_error_message"));
        }
    }

    private function getCurrentUserFromGetRequest(): User {
        if (isset($_GET["user"])) {
            return $this->getUserFromDatabase($_GET["user"]);
        } else {
            return $this->authorizationDao->getUser($_SESSION["username"]);
        }
    }

    private function getCurrentUserFromPostRequest(): User {
        return $this->getUserFromDatabase($_POST["user_id"]);
    }

    private function getUserFromDatabase($user_id): User {
        return $this->authorizationDao->getUserById($user_id);
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