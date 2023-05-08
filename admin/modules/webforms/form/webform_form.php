<?php

    defined("_ACCESS") or die;

    require_once CMS_ROOT . "core/form/form.php";
    require_once CMS_ROOT . "modules/webforms/form/webform_textfield_form.php";
    require_once CMS_ROOT . "modules/webforms/form/webform_textarea_form.php";
    require_once CMS_ROOT . "modules/webforms/form/webform_dropdown_form.php";
    require_once CMS_ROOT . "modules/webforms/webform_item_factory.php";
    require_once CMS_ROOT . 'database/dao/webform_dao.php';

    class WebFormForm extends Form {

        private WebFormItemFactory $_webform_item_factory;
        private WebForm $_webform;
        private array $_handler_properties = array();
        private WebFormDao $_webform_dao;
        private array $_form_field_forms = array();

        public function __construct(WebForm $webform) {
            $this->_webform = $webform;
            $this->_webform_item_factory = WebFormItemFactory::getInstance();
            $this->_webform_dao = WebFormDao::getInstance();
            $this->loadHandlerProperties();
        }

        public function loadFields(): void {
            $this->_webform->setTitle($this->getMandatoryFieldValue("title", "webforms_editor_title_error_message"));
            $this->_webform->setIncludeCaptcha($this->getCheckboxValue('include_captcha'));

            foreach ($this->_webform->getFormFields() as $form_field) {
                $form = $this->_webform_item_factory->getBackendFormFor($form_field);
                if ($form->supports($form_field->getType())) {
                    $form->loadFields();
                }
            }

            foreach ($this->_handler_properties as &$property) {
                $property['value'] = $this->getMandatoryFieldValue('handler_property_' . $property['id'] . '_' . $property['name'] . '_field', 'webforms_editor_handler_property_mandatory_error_message');
            }

            if ($this->hasErrors()) {
                throw new FormException();
            }
        }

        public function getHandlerProperties(): array {
            return $this->_handler_properties;
        }

        private function loadHandlerProperties(): void {
            foreach ($this->_webform_dao->getHandlersFor($this->_webform) as $handler) {
                foreach ($this->_webform_dao->getPropertiesFor($handler['id']) as $property) {
                    $this->_handler_properties[] = $property;
                }
            }
        }
    }
