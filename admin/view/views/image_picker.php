<?php
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/object_picker.php";
    
    class ImagePicker extends ObjectPicker {
        
        public function __construct(string $label, string $value, string $backing_field_id, string $opener_submit_id, ?string $button_id) {
            parent::__construct($label, $value, $backing_field_id, $opener_submit_id, $button_id);
        }
        
        public function getType(): string {
            return Search::$IMAGES;
        }
    
    }