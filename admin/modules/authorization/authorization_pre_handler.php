<?php
    defined("_ACCESS") or die;
    
    require_once CMS_ROOT . "request_handlers/http_request_handler.php";
    require_once CMS_ROOT . "modules/authorization/authorization_form.php";
    require_once CMS_ROOT . "database/dao/authorization_dao.php";
    require_once CMS_ROOT . "utilities/password_utility.php";
    
    class AuthorizationPreHandler extends HttpRequestHandler {

        private $_authorization_dao;
        private $_current_user;
        
        public function __construct() {
            $this->_authorization_dao = AuthorizationDao::getInstance();
        }
    
        public function handleGet() {
            $this->_current_user = $this->getCurrentUserFromGetRequest();
        }
        
        public function handlePost() {
            $this->_current_user = $this->getCurrentUserFromPostRequest();
            if ($this->isUpdateUserAction())
                $this->updateUser();
            if ($this->isAddUserAction())
                $this->addUser();
            if ($this->isDeleteUserAction())
                $this->deleteUser();
        }
        
        public function getCurrentUser() {
            return $this->_current_user;
        }
        
        private function addUser() {    
            $new_user = $this->_authorization_dao->createUser();
            $password = PasswordUtility::generatePassword();
            $new_user->setUuid(uniqid());
            $new_user->setPassword($password);
            $this->_authorization_dao->updateUser($new_user);
            $this->sendSuccessMessage("Gebruiker aangemaakt, met wachtwoord: " . $password);
            $this->redirectTo($this->getBackendBaseUrl() . "&user=" . $new_user->getId());
            exit();
        }
        
        private function deleteUser() {
            $this->_authorization_dao->deleteUser($this->_current_user->getId());
            $this->sendSuccessMessage("Gebruiker succesvol verwijderd");
            $this->redirectTo($this->getBackendBaseUrl());
        }
        
        private function updateUser() {
            $authorization_form = new AuthorizationForm($this->_current_user, $this->_authorization_dao);
            try {
                $authorization_form->loadFields();
                $this->_authorization_dao->updateUser($this->_current_user);
                $this->sendSuccessMessage("Gebruiker succesvol opgeslagen");
            } catch (FormException $e) {
                $this->sendErrorMessage("Gebruiker niet opgeslagen, verwerk de fouten");
            }
        }
        
        private function getCurrentUserFromGetRequest() {
            if (isset($_GET["user"]))
                return $this->getUserFromDatabase($_GET["user"]);
            else
                return $this->_authorization_dao->getUser($_SESSION["username"]);
        }
        
        private function getCurrentUserFromPostRequest() {
            return $this->getUserFromDatabase($_POST["user_id"]);
        }
        
        private function getUserFromDatabase($user_id) {
            return $this->_authorization_dao->getUserById($user_id);
        }
            
        private function isUpdateUserAction() {
            return isset($_POST["action"]) && $_POST["action"] == "update_user";
        }
            
        private function isAddUserAction() {
            return isset($_POST["action"]) && $_POST["action"] == "add_user";
        }
            
        private function isDeleteUserAction() {
            return isset($_POST["action"]) && $_POST["action"] == "delete_user";
        }
        
    }
    
?>