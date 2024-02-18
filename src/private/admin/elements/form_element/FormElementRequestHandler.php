<?php
require_once CMS_ROOT . "/request_handlers/HttpRequestHandler.php";
require_once CMS_ROOT . "/elements/form_element/FormElementForm.php";
require_once CMS_ROOT . "/database/dao/ElementDaoMysql.php";
require_once CMS_ROOT . "/elements/ElementContainsErrorsException.php";

class FormElementRequestHandler extends HttpRequestHandler {

    private FormElement $formElement;
    private FormElementForm $formElementForm;
    private ElementDao $elementDao;

    public function __construct(FormElement $formElement) {
        $this->formElement = $formElement;
        $this->formElementForm = new FormElementForm($this->formElement);
        $this->elementDao = ElementDaoMysql::getInstance();
    }

    public function handleGet(): void {}

    public function handlePost(): void {
        try {
            $this->formElementForm->loadFields();
            $this->elementDao->updateElement($this->formElement);
        } catch (FormException $e) {
            throw new ElementContainsErrorsException("Article overview element contains errors");
        }
    }
}

?>