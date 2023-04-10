<?php

    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/search.php";
    
    class ObjectPicker extends FormField {
            
        private static $TEMPLATE = "system/element_holder_picker.tpl";
        
        private $_label;
        private $_value;
        private $_backing_field_id;
        private $_button_label;
        private $_button_id;
        private $_opener_submit_id;
        
        public function __construct($label, $value, $backing_field_id, $button_label, $opener_submit_id, $button_id = "") {
            parent::__construct(null, $value, $label, false, false, null);
            $this->_label = $label;
            $this->_value = $value;
            $this->_backing_field_id = $backing_field_id;
            $this->_button_label = $button_label;
            $this->_button_id = $button_id;
            $this->_opener_submit_id = $opener_submit_id;
        }
        
        public function getType() {
            return Search::$ELEMENT_HOLDERS;
        }
    
        public function render(): string {
            $picker_button = new Button($this->_button_id, $this->getTextResource('object_picker_button_title'), "window.open('" . $this->getBackendBaseUrlRaw() . "?"
                                        . Search::$POPUP_TYPE_KEY . "=search&amp;" . Search::$OBJECT_TO_SEARCH_KEY . "=" 
                                        . $this->getType() . "&amp;" . Search::$BACK_CLICK_ID_KEY . "=" . $this->_opener_submit_id 
                                        . "&amp;" . Search::$BACKFILL_KEY . "=" . $this->_backing_field_id . "', '" . $this->_button_label 
                                        . "', 'width=950,height=600,scrollbars=yes,toolbar=no,location=yes'); return false;");

            $this->getTemplateEngine()->assign("picker_button", $picker_button->render());
            $this->getTemplateEngine()->assign("backing_field_id", $this->_backing_field_id);
            $this->getTemplateEngine()->assign("value", $this->_value);
            $this->getTemplateEngine()->assign("button_label", $this->_button_label);
            $this->getTemplateEngine()->assign("opener_submit_id", $this->_opener_submit_id);
            $this->getTemplateEngine()->assign("label", $this->getInputLabelHtml($this->_label, $this->_backing_field_id, false));
            
            return $this->getTemplateEngine()->fetch(self::$TEMPLATE);
        }
    
    }

?>