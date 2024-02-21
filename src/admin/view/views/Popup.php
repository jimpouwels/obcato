<?php

namespace Obcato\Core\admin\view\views;

use Obcato\ComponentApi\TemplateEngine;

class Popup extends Visual {

    private string $popupType;

    public function __construct(TemplateEngine $templateEngine, string $popupType) {
        parent::__construct($templateEngine);
        $this->popupType = $popupType;
    }

    public function getTemplateFilename(): string {
        return "system/popup.tpl";
    }

    public function load(): void {
        $content = null;
        if ($this->popupType == "search") {
            $content = new Search($this->getTemplateEngine());
        }
        $this->assign("content", $content->render());
    }

}