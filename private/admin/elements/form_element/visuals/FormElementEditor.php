<?php
require_once CMS_ROOT . "/view/views/ElementVisual.php";
require_once CMS_ROOT . "/view/views/TextField.php";
require_once CMS_ROOT . "/view/views/Pulldown.php";

class FormElementEditor extends ElementVisual {

    private static string $TEMPLATE = "elements/form_element/form_element_form.tpl";
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

    public function renderElementForm(Smarty_Internal_Data $data): string {
        $titleField = new TextField($this->createFieldId("title"), $this->getTextResource("form_element_editor_title"), htmlentities($this->formElement->getTitle()), false, false, null);
        $currentSelectedWebform = $this->formElement->getWebForm();
        $currentWebformId = null;
        if ($currentSelectedWebform) {
            $currentWebformId = $currentSelectedWebform->getId();
        }
        $webformPicker = new Pulldown($this->createFieldId("selected_webform"), $this->getTextResource("form_element_editor_webform"), $currentWebformId, array(), false, null, true);
        foreach ($this->webformDao->getAllWebForms() as $webform) {
            $webformPicker->addOption($webform->getTitle(), $webform->getId());
        }

        $data->assign("title_field", $titleField->render());
        $data->assign("webform_picker", $webformPicker->render());
        return $this->getTemplateEngine()->fetch(self::$TEMPLATE, $data);
    }

    private function createFieldId($propertyName): string {
        return "element_{$this->formElement->getId()}_{$propertyName}";
    }

}

?>
