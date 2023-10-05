<?php
require_once CMS_ROOT . "/core/form/Form.php";
require_once CMS_ROOT . "/modules/webforms/form/WebformTextFieldForm.php";
require_once CMS_ROOT . "/modules/webforms/form/WebformTextAreaForm.php";
require_once CMS_ROOT . "/modules/webforms/form/WebformDropDownForm.php";
require_once CMS_ROOT . "/modules/webforms/WebformItemFactory.php";
require_once CMS_ROOT . '/database/dao/WebformDaoMysql.php';

class WebformForm extends Form {

    private WebformItemFactory $_webform_item_factory;
    private WebformHandlerManager $_webform_handler_manager;
    private WebForm $_webform;
    private array $_handler_properties = array();
    private ?string $_captcha_secret = null;
    private WebformDao $_webform_dao;

    public function __construct(WebForm $webform) {
        $this->_webform = $webform;
        $this->_webform_item_factory = WebformItemFactory::getInstance();
        $this->_webform_dao = WebformDaoMysql::getInstance();
        $this->_webform_handler_manager = WebformHandlerManager::getInstance();
    }

    public function loadFields(): void {
        $this->_webform->setTitle($this->getMandatoryFieldValue("title"));
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
            $this->_webform->setCaptchaKey($this->getMandatoryFieldValue('captcha_key'));
            $this->_captcha_secret = $this->getMandatoryFieldValue('captcha_secret');
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
            $property->setValue($this->getMandatoryFieldValue("handler_property_{$property->getId()}_field"));
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
