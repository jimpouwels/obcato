<?php

namespace Obcato\Core\admin\view\views;

use Obcato\ComponentApi\TemplateEngine;

class ImagePicker extends ObjectPicker {

    public function __construct(TemplateEngine $templateEngine, string $field_name, string $label, ?string $value, string $opener_click_id) {
        parent::__construct($templateEngine, $field_name, $label, $value, $opener_click_id);
    }

    public function getType(): string {
        return Search::$IMAGES_POPUP_TYPE;
    }

}