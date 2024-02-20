<?php

namespace Obcato\Core;

use Obcato\ComponentApi\TemplateEngine;
use Obcato\ComponentApi\Visual;

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
            $content = new Search();
        }
        $this->assign("content", $content->render());
    }

}