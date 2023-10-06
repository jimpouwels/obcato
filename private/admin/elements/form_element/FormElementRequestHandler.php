<?php
require_once CMS_ROOT . "/request_handlers/HttpRequestHandler.php";
require_once CMS_ROOT . "/elements/form_element/FormElementForm.php";
require_once CMS_ROOT . "/database/dao/ElementDaoMysql.php";
require_once CMS_ROOT . "/elements/ElementContainsErrorsException.php";

class FormElementRequestHandler extends HttpRequestHandler {

    private FormElement $_form_element;
    private FormElementForm $_form_element_form;
    private ElementDao $_element_dao;

    public function __construct(FormElement $form_element) {
        $this->_form_element = $form_element;
        $this->_form_element_form = new FormElementForm($this->_form_element);
        $this->_element_dao = ElementDaoMysql::getInstance();
    }

    public function handleGet(): void {}

    public function handlePost(): void {
        try {
            $this->_form_element_form->loadFields();
            $this->_element_dao->updateElement($this->_form_element);
        } catch (FormException $e) {
            throw new ElementContainsErrorsException("Article overview element contains errors");
        }
    }
}

?>