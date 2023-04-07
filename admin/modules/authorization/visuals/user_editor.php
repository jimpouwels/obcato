<?php
    defined('_ACCESS') or die;

    class UserEditor extends Panel {

        private static $USER_EDITOR_TEMPLATE = "modules/authorization/user_editor.tpl";
        private $_template_engine;
        private $_current_user;

        public function __construct($current_user) {
            parent::__construct('Algemeen', 'user_meta');
            $this->_current_user = $current_user;
            $this->_template_engine = TemplateEngine::getInstance();
        }

        public function renderVisual(): string {
            return parent::renderVisual();
        }

        public function renderPanelContent() {
            $this->_template_engine->assign("user_id", $this->_current_user->getId());
            $this->_template_engine->assign("username_field", $this->renderUserNameField());
            $this->_template_engine->assign("firstname_field", $this->renderFirstNameField());
            $this->_template_engine->assign("prefix_field", $this->renderPrefixField());
            $this->_template_engine->assign("lastname_field", $this->renderLastNameField());
            $this->_template_engine->assign("email_field", $this->renderEmailField());
            if ($this->_current_user->isLoggedInUser()) {
                $this->_template_engine->assign("new_password_first", $this->renderFirstPasswordField());
                $this->_template_engine->assign("new_password_second", $this->renderSecondPasswordField());
            }
            return $this->_template_engine->fetch(self::$USER_EDITOR_TEMPLATE);
        }

        private function renderUserNameField() {
            $username_field = new TextField("user_username", "Gebruikersnaam", $this->_current_user->getUsername(), true, false, null);
            return $username_field->render();
        }

        private function renderFirstNameField() {
            $firstname_field = new TextField("user_firstname", "Voornaam", $this->_current_user->getFirstName(), true, false, "user_firstname_field");
            return $firstname_field->render();
        }

        private function renderPrefixField() {
            $prefix_field = new TextField("user_prefix", "Tussenvoegsel", $this->_current_user->getPrefix(), false, false, "user_prefix_field");
            return $prefix_field->render();
        }

        private function renderLastNameField() {
            $lastname_field = new TextField("user_lastname", "Achternaam", $this->_current_user->getLastName(), true, false, "user_lastname_field");
            return $lastname_field->render();
        }

        private function renderEmailField() {
            $email_field = new TextField("user_email", "E-mail adres", $this->_current_user->getEmailAddress(), true, false, "user_email_field");
            return $email_field->render();
        }

        private function renderFirstPasswordField() {
            $first_password_field = new PasswordField("user_new_password_first", "Nieuw wachtwoord", "", false, "user_password_field");
            return $first_password_field->render();
        }

        private function renderSecondPasswordField() {
            $second_password_field = new PasswordField("user_new_password_second", "Herhaal wachtwoord", "", false, "user_password_field");
            return $second_password_field->render();
        }
    }
