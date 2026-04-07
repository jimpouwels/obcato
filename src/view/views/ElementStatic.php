<?php

namespace Obcato\Core\view\views;

abstract class ElementStatic extends Visual {

    abstract public function renderStyles(): array;

    abstract public function renderScripts(): array;

    public function getTemplateFilename(): string {
        return "";
    }

    public function load(): void {}

}
