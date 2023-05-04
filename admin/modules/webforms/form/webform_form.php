<?php

    defined("_ACCESS") or die;

    require_once CMS_ROOT . "core/form/form.php";
    require_once CMS_ROOT . "modules/webforms/form/webform_textfield_form.php";
    require_once CMS_ROOT . "modules/webforms/form/webform_textarea_form.php";
    require_once CMS_ROOT . "modules/webforms/form/webform_dropdown_form.php";

    class WebFormForm extends Form {

        private WebForm $_webform;
        private array $_form_field_forms = array();

        public function __construct(WebForm $webform) {
            $this->_webform = $webform;
        }

        public function loadFields(): void {
            $this->_webform->setTitle($this->getMandatoryFieldValue("title", "webforms_editor_title_error_message"));

            foreach ($this->_webform->getFormFields() as $form_field) {
                $form = $this->getFormForField($form_field);
                if ($form->supports($form_field->getType())) {
                    $form->loadFields();
                }
            }
            if ($this->hasErrors()) {
                throw new FormException();
            }
        }

        private function getFormForField(WebFormField $webform_field): ?Form {
            if ($webform_field->getType() == WebFormTextField::$TYPE) {
                return new WebFormTextFieldForm($webform_field);
            } else if ($webform_field->getType() == WebFormTextArea::$TYPE) {
                return new WebFormTextAreaForm($webform_field);
            } else if ($webform_field->getType() == WebFormDropDown::$TYPE) {
                return new WebFormDropDownForm($webform_field);
            }
            return null;
        }
    }
