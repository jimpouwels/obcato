<?php
defined("_ACCESS") or die;

require_once CMS_ROOT . "/request_handlers/element_form.php";

class FormElementForm extends ElementForm {

    private WebFormDao $_webform_dao;
    private FormElement $_form_element;

    public function __construct(FormElement $form_element) {
        parent::__construct($form_element);
        $this->_webform_dao = WebFormDao::getInstance();
        $this->_form_element = $form_element;
    }

    public function loadFields(): void {
        parent::loadFields();
        $this->_form_element->setTitle($this->getFieldValue("element_{$this->_form_element->getId()}_title"));
        $webform_id_string_val = $this->getFieldValue("element_{$this->_form_element->getId()}_selected_webform", "This is not a valid webform id)");
        $webform = null;
        if (!empty($webform_id_string_val)) {
            $webform = $this->_webform_dao->getWebForm(intval($webform_id_string_val));
        }
        $this->_form_element->setWebForm($webform);
        if ($this->hasErrors()) {
            throw new FormException();
        }
    }

}