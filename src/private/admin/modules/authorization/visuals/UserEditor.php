<?php

class UserEditor extends Panel {

    private ?User $currentUser;

    public function __construct(TemplateEngine $templateEngine, $currentUser) {
        parent::__construct($templateEngine, $currentUser->getFullName(), 'user_meta');
        $this->currentUser = $currentUser;
    }

    public function getPanelContentTemplate(): string {
        return "modules/authorization/user_editor.tpl";
    }

    public function loadPanelContent(Smarty_Internal_Data $data): void {
        $data->assign("user_id", $this->currentUser->getId());
        $data->assign("username_field", $this->renderUserNameField());
        $data->assign("firstname_field", $this->renderFirstNameField());
        $data->assign("prefix_field", $this->renderPrefixField());
        $data->assign("lastname_field", $this->renderLastNameField());
        $data->assign("email_field", $this->renderEmailField());
        if ($this->currentUser->isLoggedInUser()) {
            $data->assign("new_password_first", $this->renderFirstPasswordField());
            $data->assign("new_password_second", $this->renderSecondPasswordField());
        }
    }

    private function renderUserNameField(): string {
        $usernameField = new TextField($this->getTemplateEngine(), "user_username", "users_editor_username_field_label", $this->currentUser->getUsername(), true, false, null);
        return $usernameField->render();
    }

    private function renderFirstNameField(): string {
        $firstnameField = new TextField($this->getTemplateEngine(), "user_firstname", "users_editor_firstname_field_label", $this->currentUser->getFirstName(), true, false, "user_firstname_field");
        return $firstnameField->render();
    }

    private function renderPrefixField(): string {
        $prefixField = new TextField($this->getTemplateEngine(), "user_prefix", "users_editor_prefix_field_label", $this->currentUser->getPrefix(), false, false, "user_prefix_field");
        return $prefixField->render();
    }

    private function renderLastNameField(): string {
        $lastnameField = new TextField($this->getTemplateEngine(), "user_lastname", "users_editor_lastname_field_label", $this->currentUser->getLastName(), true, false, "user_lastname_field");
        return $lastnameField->render();
    }

    private function renderEmailField(): string {
        $emailField = new TextField($this->getTemplateEngine(), "user_email", "users_editor_email_address_field_label", $this->currentUser->getEmailAddress(), true, false, "user_email_field");
        return $emailField->render();
    }

    private function renderFirstPasswordField(): string {
        $firstPasswordField = new PasswordField($this->getTemplateEngine(), "user_new_password_first", "users_editor_new_pwd_field_label", "", false, "user_password_field");
        return $firstPasswordField->render();
    }

    private function renderSecondPasswordField(): string {
        $secondPasswordField = new PasswordField($this->getTemplateEngine(), "user_new_password_second", "users_editor_new_pwd_repeat_field_label", "", false, "user_password_field");
        return $secondPasswordField->render();
    }
}
