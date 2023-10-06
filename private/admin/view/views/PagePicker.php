<?php
require_once CMS_ROOT . "/view/views/ObjectPicker.php";

class PagePicker extends ObjectPicker {

    public function __construct(string $name, string $labelResourceIdentifier, ?string $value, string $opener_click_id) {
        parent::__construct($name, $labelResourceIdentifier, $value, $opener_click_id);
    }

    public function getType(): string {
        return Search::$PAGES;
    }

}