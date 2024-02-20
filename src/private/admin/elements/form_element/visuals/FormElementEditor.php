<?php

namespace Obcato\Core;

use Obcato\ComponentApi\TemplateData;
use Obcato\ComponentApi\TemplateEngine;

class FormElementEditor extends ElementVisual {

    private static string $TEMPLATE = "elements/form_element/form_element_form.tpl";
    private FormElement $formElement;
    private WebformDao $webformDao;

    public function __construct(TemplateEngine $templateEngine, FormElement $formElement) {
        parent::__construct($templateEngine);
        $this->formElement = $formElement;
        $this->webformDao = WebformDaoMysql::getInstance();
    }

    public function getElement(): Element {
        return $this->formElement;
    }

    public function getElementFormTemplateFilename(): string {
        return self::$TEMPLATE;
    }

    public function loadElementForm(TemplateData $data): void {
        $titleField = new TextField($this->getTemplateEngine(), $this->createFieldId("title"), $this->getTextResource("form_element_editor_title"), htmlentities($this->formElement->getTitle()), false, false, null);
        $currentSelectedWebform = $this->formElement->getWebForm();
        $currentWebformId = $currentSelectedWebform?->getId();
        $webformPicker = new Pulldown($this->getTemplateEngine(), $this->createFieldId("selected_webform"), $this->getTextResource("form_element_editor_webform"), $currentWebformId, array(), false, null, true);
        foreach ($this->webformDao->getAllWebForms() as $webform) {
            $webformPicker->addOption($webform->getTitle(), $webform->getId());
        }

        $data->assign("title_field", $titleField->render());
        $data->assign("webform_picker", $webformPicker->render());
    }

    private function createFieldId($propertyName): string {
        return "element_{$this->formElement->getId()}_{$propertyName}";
    }

}