<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/modules/webforms/visuals/webforms/fields/WebformItemVisual.php";
require_once CMS_ROOT . "/core/model/WebformButton.php";

class WebFormButtonVisual extends WebFormItemVisual {

    public function __construct(WebFormItem $webform_item) {
        parent::__construct($webform_item);
    }

    public function getFormItemTemplate(): string {
        return "modules/webforms/webforms/fields/webform_button.tpl";
    }

    public function loadItemContent(Smarty_Internal_Data $data): void {}
}

?>