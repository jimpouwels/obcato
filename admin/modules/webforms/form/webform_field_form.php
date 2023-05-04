<?php

    defined("_ACCESS") or die;

    require_once CMS_ROOT . "core/form/form.php";
    require_once CMS_ROOT . "core/model/webform_field.php";

    abstract class WebFormFieldForm extends Form {

        private WebFormField $_webform_field;

        public function __construct(WebFormField $webform_field) {
            $this->_webform_field = $webform_field;
        }

        public function loadFields(): void {
            $this->_webform_field->setLabel($this->getMandatoryFieldValue("webform_field_{$this->_webform_field->getId()}_label", "webforms_editor_title_error_message"));
            $this->_webform_field->setName($this->getMandatoryFieldValue("webform_field_{$this->_webform_field->getId()}_name", "webforms_editor_title_error_message"));
            $this->loadCustomFields($this->_webform_field);
            if ($this->hasErrors()) {
                throw new FormException();
            }
        }

        public abstract function loadCustomFields(WebFormField $webform_field): void;

        protected function getFormField(): WebFormField {
            return $this->_webform_field;
        }
    }
