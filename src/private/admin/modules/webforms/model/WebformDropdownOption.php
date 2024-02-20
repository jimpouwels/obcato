<?php

namespace Obcato\Core;

class WebFormDropDownOption extends Entity {

    private string $_text;
    private string $_name;

    public function __construct(string $text, string $name) {
        $this->_text = $text;
        $this->_name = $name;
    }

    public function getText(): string {
        return $this->_text;
    }

    public function setText(string $text): void {
        $this->_text = $text;
    }

    public function getName(): string {
        return $this->_name;
    }

    public function setName(string $name): void {
        $this->_name = $name;
    }

}