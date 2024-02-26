<?php

namespace Obcato\Core\elements\form_element;

use Obcato\Core\core\form\FormException;
use Obcato\Core\database\dao\WebformDao;
use Obcato\Core\database\dao\WebformDaoMysql;
use Obcato\Core\request_handlers\ElementForm;

class FormElementForm extends ElementForm {

    private WebformDao $webformDao;
    private FormElement $formElement;

    public function __construct(FormElement $formElement) {
        parent::__construct($formElement);
        $this->webformDao = WebformDaoMysql::getInstance();
        $this->formElement = $formElement;
    }

    public function loadFields(): void {
        parent::loadFields();
        $this->formElement->setTitle($this->getFieldValue("element_{$this->formElement->getId()}_title"));
        $webformIdStringVal = $this->getFieldValue("element_{$this->formElement->getId()}_selected_webform", "This is not a valid webform id)");
        $webform = null;
        if (!empty($webformIdStringVal)) {
            $webform = $this->webformDao->getWebForm(intval($webformIdStringVal));
        }
        $this->formElement->setWebForm($webform);
        if ($this->hasErrors()) {
            throw new FormException();
        }
    }

}