<?php

defined("_ACCESS") or die;

require_once CMS_ROOT . "/core/form/Form.php";
require_once CMS_ROOT . "/modules/webforms/form/WebFormTextFieldForm.php";
require_once CMS_ROOT . "/modules/webforms/form/WebFormTextAreaForm.php";
require_once CMS_ROOT . "/modules/webforms/form/WebFormDropDownForm.php";
require_once CMS_ROOT . "/modules/webforms/WebFormItemFactory.php";
require_once CMS_ROOT . '/database/dao/WebformDaoMysql.php';

class WebFormForm extends Form {

    private WebFormItemFactory $_webform_item_factory;
    private WebFormHandlerManager $_webform_handler_manager;
    private WebForm $_webform;
    private array $_handler_properties = array();
    private ?string $_captcha_secret = null;
    private WebFormDao $_webform_dao;

    public function __construct(WebForm $webform) {
        $this->_webform = $webform;
        $this->_webform_item_factory = WebFormItemFactory::getInstance();
        $this->_webform_dao = WebFormDaoMysql::getInstance();
        $this->_webform_handler_manager = WebFormHandlerManager::getInstance();
    }

    public function loadFields(): void {
        $this->_webform->setTitle($this->getMandatoryFieldValue("title", "webforms_editor_title_error_message"));
        $this->_webform->setTemplateId($this->getFieldValue("template"));
        $this->_webform->setIncludeCaptcha($this->getCheckboxValue('include_captcha'));

        // delete properties that are no longer supposed to be there
        $all_handlers_instances = $this->_webform_dao->getWebFormHandlersFor($this->_webform);
        foreach ($all_handlers_instances as $handler_instance) {
            foreach ($handler_instance->getProperties() as $property) {
                if (!Arrays::firstMatch($this->_webform_handler_manager->getHandler($handler_instance->getType())->getRequiredProperties(), function ($required_property) use ($property) {
                    return $property->getName() == $required_property->getName();
                })) {
                    $this->_webform_dao->deleteProperty($property);
                }
            }
        }
        $this->loadHandlerProperties();

        if ($this->_webform->getIncludeCaptcha()) {
            $this->_webform->setCaptchaKey($this->getMandatoryFieldValue('captcha_key', 'webforms_editor_captcha_key_error_message'));
            $this->_captcha_secret = $this->getMandatoryFieldValue('captcha_secret', 'webforms_editor_captcha_secret_error_message');
        }

        $item_order = $this->getFieldValue('draggable_order');
        $item_order_arr = array();
        if ($item_order) {
            $item_order_arr = explode(',', $item_order);
        }
        foreach ($this->_webform->getFormFields() as $form_field) {
            if (count($item_order_arr) > 0) {
                $form_field->setOrderNr(array_search($form_field->getId(), $item_order_arr));
            }
            $form = $this->_webform_item_factory->getBackendFormFor($form_field);
            if ($form->supports($form_field->getType())) {
                $form->loadFields();
            }
        }

        foreach ($this->_handler_properties as $property) {
            $property->setValue($this->getMandatoryFieldValue("handler_property_{$property->getId()}_field", 'webforms_editor_handler_property_mandatory_error_message'));
        }

        if ($this->hasErrors()) {
            throw new FormException();
        }
    }

    public function getHandlerProperties(): array {
        return $this->_handler_properties;
    }

    public function getCaptchaSecret(): ?string {
        return $this->_captcha_secret;
    }

    private function loadHandlerProperties(): void {
        foreach ($this->_webform_dao->getWebFormHandlersFor($this->_webform) as $handler_instance) {
            $this->_handler_properties = array_merge($this->_handler_properties, $handler_instance->getProperties());
        }
    }
}
