<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "view/views/object_picker.php";

class ImagePicker extends ObjectPicker {

    public function __construct(string $field_name, string $label, ?string $value, string $opener_click_id) {
        parent::__construct($field_name, $label, $value, $opener_click_id);
    }

    public function getType(): string {
        return Search::$IMAGES_POPUP_TYPE;
    }

}