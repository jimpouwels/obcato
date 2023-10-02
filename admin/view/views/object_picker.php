<?php

defined('_ACCESS') or die;

require_once CMS_ROOT . "view/views/search.php";

class ObjectPicker extends FormField {

    private ?string $_value = null;
    private string $_opener_click_id;

    public function __construct(string $field_name, string $label_resource_identifier, ?string $value, string $opener_click_id) {
        parent::__construct($field_name, $value, $label_resource_identifier, false, false, null);
        $this->_value = $value;
        $this->_opener_click_id = $opener_click_id;
    }

    public function getFormFieldTemplateFilename(): string {
        return "system/object_picker.tpl";
    }

    public function getType(): string {
        return Search::$ELEMENT_HOLDERS;
    }

    public function loadFormField($data): void {
        $picker_button = new Button(null, $this->getTextResource('object_picker_button_title'), "window.open('" . $this->getBackendBaseUrlRaw() . "?"
            . Search::$POPUP_TYPE_KEY . "=search&amp;" . Search::$OBJECT_TO_SEARCH_KEY . "="
            . $this->getType() . "&amp;" . Search::$BACK_CLICK_ID_KEY . "=" . $this->_opener_click_id
            . "&amp;" . Search::$BACKFILL_KEY . "=" . $this->getFieldName() . "', '" . $this->getTextResource("select_field_default_text")
            . "', 'width=950,height=600,scrollbars=yes,toolbar=no,location=yes'); return false;");

        $data->assign("picker_button", $picker_button->render());
        $data->assign("value", $this->_value);
    }

    public function getFieldType(): string {
        return 'objectpicker';
    }

}
