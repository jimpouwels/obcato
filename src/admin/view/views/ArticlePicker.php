<?php

namespace Obcato\Core\admin\view\views;

class ArticlePicker extends ObjectPicker {

    public function __construct(string $name, string $labelResourceIdentifier, ?string $value, string $opener_click_id) {
        parent::__construct($name, $labelResourceIdentifier, $value, $opener_click_id);
    }

    public function getType(): string {
        return Search::$ARTICLES;
    }

}