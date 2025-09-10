<?php

namespace Obcato\Core\elements\form_element\visuals;

use Obcato\Core\core\model\Element;
use Obcato\Core\database\dao\WebformDao;
use Obcato\Core\database\dao\WebformDaoMysql;
use Obcato\Core\elements\form_element\FormElement;
use Obcato\Core\view\TemplateData;
use Obcato\Core\view\views\ElementVisual;
use Obcato\Core\view\views\Pulldown;
use Obcato\Core\view\views\TextField;

class FormElementEditor extends ElementVisual {

    private static string $TEMPLATE = "form_element/templates/form_element_form.tpl";
    private FormElement $formElement;
    private WebformDao $webformDao;

    public function __construct(FormElement $formElement) {
        parent::__construct();
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
        $titleField = new TextField($this->createFieldId("title"), $this->getTextResource("form_element_editor_title"), htmlentities($this->formElement->getTitle()), false, false, null);
        $currentSelectedWebform = $this->formElement->getWebForm();
        $currentWebformId = $currentSelectedWebform?->getId();
        $webformPicker = new Pulldown($this->createFieldId("selected_webform"), $this->getTextResource("form_element_editor_webform"), $currentWebformId, array(), false, null, true);
        foreach ($this->webformDao->getAllWebForms() as $webform) {
            $webformPicker->addOption($webform->getTitle(), $webform->getId());
        }

        $data->assign("title_field", $titleField->render());
        $data->assign("webform_picker", $webformPicker->render());
    }

    public function includeLinkSelector(): bool
    {
        return false;
    }

    private function createFieldId($propertyName): string {
        return "element_{$this->formElement->getId()}_{$propertyName}";
    }

}