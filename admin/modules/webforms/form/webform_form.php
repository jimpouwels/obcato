<?php

    defined("_ACCESS") or die;

    require_once CMS_ROOT . "core/form/form.php";
    require_once CMS_ROOT . "modules/webforms/form/webform_textfield_form.php";
    require_once CMS_ROOT . "modules/webforms/form/webform_textarea_form.php";
    require_once CMS_ROOT . "modules/webforms/form/webform_dropdown_form.php";
    require_once CMS_ROOT . "modules/webforms/webform_field_factory.php";

    class WebFormForm extends Form {

        private WebFormFieldFactory $_webform_field_factory;
        private WebForm $_webform;
        private array $_form_field_forms = array();

        public function __construct(WebForm $webform) {
            $this->_webform = $webform;
            $this->_webform_field_factory = WebFormFieldFactory::getInstance();
        }

        public function loadFields(): void {
            $this->_webform->setTitle($this->getMandatoryFieldValue("title", "webforms_editor_title_error_message"));

            foreach ($this->_webform->getFormFields() as $form_field) {
                $form = $this->_webform_field_factory->getBackendFormFor($form_field);
                if ($form->supports($form_field->getType())) {
                    $form->loadFields();
                }
            }
            if ($this->hasErrors()) {
                throw new FormException();
            }
        }
    }
