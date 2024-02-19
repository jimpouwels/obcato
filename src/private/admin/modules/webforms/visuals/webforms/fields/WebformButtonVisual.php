<?php
require_once CMS_ROOT . "/modules/webforms/visuals/webforms/fields/WebformItemVisual.php";
require_once CMS_ROOT . "/modules/webforms/model/WebformButton.php";

class WebformButtonVisual extends WebformItemVisual {

    public function __construct(TemplateEngine $templateEngine, WebFormItem $webform_item) {
        parent::__construct($templateEngine, $webform_item);
    }

    public function getFormItemTemplate(): string {
        return "modules/webforms/webforms/fields/webform_button.tpl";
    }

    public function loadItemContent(TemplateData $data): void {}
}