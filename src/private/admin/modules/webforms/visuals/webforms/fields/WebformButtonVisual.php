<?php
require_once CMS_ROOT . "/modules/webforms/visuals/webforms/fields/WebformItemVisual.php";
require_once CMS_ROOT . "/modules/webforms/model/WebformButton.php";

class WebformButtonVisual extends WebformItemVisual {

    public function __construct(WebFormItem $webform_item) {
        parent::__construct($webform_item);
    }

    public function getFormItemTemplate(): string {
        return "modules/webforms/webforms/fields/webform_button.tpl";
    }

    public function loadItemContent(Smarty_Internal_Data $data): void {}
}

?>