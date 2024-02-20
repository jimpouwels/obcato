<?php

namespace Obcato\Core;

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