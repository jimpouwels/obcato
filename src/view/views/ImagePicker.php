<?php

namespace Obcato\Core\view\views;

class ImagePicker extends ObjectPicker {

    public function __construct(string $fieldName, string $label, ?string $value, string $opener_click_id) {
        parent::__construct($fieldName, $label, $value, $opener_click_id);
    }

    public function getType(): string {
        return Search::$IMAGES_POPUP_TYPE;
    }

}