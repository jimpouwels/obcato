<?php
    defined('_ACCESS') or die;

    class UserEditor extends Panel {

        private ?User $_current_user = null;

        public function __construct($current_user) {
            parent::__construct('Algemeen', 'user_meta');
            $this->_current_user = $current_user;
        }

        public function getPanelContentTemplate(): string {
            return "modules/authorization/user_editor.tpl";
        }

        public function loadPanelContent(Smarty_Internal_Data $data): void {
            $data->assign("user_id", $this->_current_user->getId());
            $data->assign("username_field", $this->renderUserNameField());
            $data->assign("firstname_field", $this->renderFirstNameField());
            $data->assign("prefix_field", $this->renderPrefixField());
            $data->assign("lastname_field", $this->renderLastNameField());
            $data->assign("email_field", $this->renderEmailField());
            if ($this->_current_user->isLoggedInUser()) {
                $data->assign("new_password_first", $this->renderFirstPasswordField());
                $data->assign("new_password_second", $this->renderSecondPasswordField());
            }
        }

        private function renderUserNameField(): string {
            $username_field = new TextField("user_username", "Gebruikersnaam", $this->_current_user->getUsername(), true, false, null);
            return $username_field->render();
        }

        private function renderFirstNameField(): string {
            $firstname_field = new TextField("user_firstname", "Voornaam", $this->_current_user->getFirstName(), true, false, "user_firstname_field");
            return $firstname_field->render();
        }

        private function renderPrefixField(): string {
            $prefix_field = new TextField("user_prefix", "Tussenvoegsel", $this->_current_user->getPrefix(), false, false, "user_prefix_field");
            return $prefix_field->render();
        }

        private function renderLastNameField(): string {
            $lastname_field = new TextField("user_lastname", "Achternaam", $this->_current_user->getLastName(), true, false, "user_lastname_field");
            return $lastname_field->render();
        }

        private function renderEmailField(): string {
            $email_field = new TextField("user_email", "E-mail adres", $this->_current_user->getEmailAddress(), true, false, "user_email_field");
            return $email_field->render();
        }

        private function renderFirstPasswordField(): string {
            $first_password_field = new PasswordField("user_new_password_first", "Nieuw wachtwoord", "", false, "user_password_field");
            return $first_password_field->render();
        }

        private function renderSecondPasswordField(): string {
            $second_password_field = new PasswordField("user_new_password_second", "Herhaal wachtwoord", "", false, "user_password_field");
            return $second_password_field->render();
        }
    }
