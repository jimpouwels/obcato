<?php
require_once CMS_ROOT . "/view/views/ObjectPicker.php";

class ArticlePicker extends ObjectPicker {

    public function __construct(TemplateEngine $templateEngine, string $name, string $labelResourceIdentifier, ?string $value, string $opener_click_id) {
        parent::__construct($templateEngine, $name, $labelResourceIdentifier, $value, $opener_click_id);
    }

    public function getType(): string {
        return Search::$ARTICLES;
    }

}