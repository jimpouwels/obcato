<?php

namespace Obcato\Core\admin\modules\authorization\visuals;

use Obcato\ComponentApi\TemplateData;
use Obcato\Core\admin\modules\authorization\model\User;
use Obcato\Core\admin\view\views\Panel;
use Obcato\Core\admin\view\views\PasswordField;
use Obcato\Core\admin\view\views\TextField;gi

class UserEditor extends Panel {

    private ?User $currentUser;

    public function __construct(User $currentUser) {
        parent::__construct($currentUser->getFullName(), 'user_meta');
        $this->currentUser = $currentUser;
    }

    public function getPanelContentTemplate(): string {
        return "modules/authorization/user_editor.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
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
        $usernameField = new TextField("user_username", "users_editor_username_field_label", $this->currentUser->getUsername(), true, false, null);
        return $usernameField->render();
    }

    private function renderFirstNameField(): string {
        $firstnameField = new TextField("user_firstname", "users_editor_firstname_field_label", $this->currentUser->getFirstName(), true, false, "user_firstname_field");
        return $firstnameField->render();
    }

    private function renderPrefixField(): string {
        $prefixField = new TextField("user_prefix", "users_editor_prefix_field_label", $this->currentUser->getPrefix(), false, false, "user_prefix_field");
        return $prefixField->render();
    }

    private function renderLastNameField(): string {
        $lastnameField = new TextField("user_lastname", "users_editor_lastname_field_label", $this->currentUser->getLastName(), true, false, "user_lastname_field");
        return $lastnameField->render();
    }

    private function renderEmailField(): string {
        $emailField = new TextField("user_email", "users_editor_email_address_field_label", $this->currentUser->getEmailAddress(), true, false, "user_email_field");
        return $emailField->render();
    }

    private function renderFirstPasswordField(): string {
        $firstPasswordField = new PasswordField("user_new_password_first", "users_editor_new_pwd_field_label", "", false, "user_password_field");
        return $firstPasswordField->render();
    }

    private function renderSecondPasswordField(): string {
        $secondPasswordField = new PasswordField("user_new_password_second", "users_editor_new_pwd_repeat_field_label", "", false, "user_password_field");
        return $secondPasswordField->render();
    }
}
