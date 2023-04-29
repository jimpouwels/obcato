<?php

    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/search.php";
    
    class ObjectPicker extends FormField {
        
        private ?string $_value = null;
        private string $_backing_field_id;
        private ?string $_button_id = null;
        private string $_opener_submit_id;
        
        public function __construct(string $label_resource_identifier, ?string $value, string $backing_field_id, string $opener_submit_id, ?string $button_id = null) {
            parent::__construct("", $value, $label_resource_identifier, false, false, null);
            $this->_value = $value;
            $this->_backing_field_id = $backing_field_id;
            $this->_button_id = $button_id;
            $this->_opener_submit_id = $opener_submit_id;
        }

        public function getFormFieldTemplateFilename(): string {
            return "system/object_picker.tpl";
        }
        
        public function getType(): string {
            return Search::$ELEMENT_HOLDERS;
        }
    
        public function loadFormField($data): void {
            $picker_button = new Button($this->_button_id, $this->getTextResource('object_picker_button_title'), "window.open('" . $this->getBackendBaseUrlRaw() . "?"
                                        . Search::$POPUP_TYPE_KEY . "=search&amp;" . Search::$OBJECT_TO_SEARCH_KEY . "=" 
                                        . $this->getType() . "&amp;" . Search::$BACK_CLICK_ID_KEY . "=" . $this->_opener_submit_id 
                                        . "&amp;" . Search::$BACKFILL_KEY . "=" . $this->_backing_field_id . "', '" . $this->getTextResource("select_field_default_text") 
                                        . "', 'width=950,height=600,scrollbars=yes,toolbar=no,location=yes'); return false;");

            $data->assign("picker_button", $picker_button->render());
            $data->assign("backing_field_id", $this->_backing_field_id);
            $data->assign("value", $this->_value);
        }
    
    }

?>