<?php
require_once CMS_ROOT . "/view/views/ObjectPicker.php";

class ArticlePicker extends ObjectPicker {

    public function __construct(string $name, string $label_resource_identifier, ?string $value, string $opener_click_id) {
        parent::__construct($name, $label_resource_identifier, $value, $opener_click_id);
    }

    public function getType(): string {
        return Search::$ARTICLES;
    }

}