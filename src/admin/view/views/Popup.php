<?php

namespace Obcato\Core\admin\view\views;

use Obcato\ComponentApi\TemplateEngine;

class Popup extends Visual {

    private string $popupType;

    public function __construct(string $popupType) {
        parent::__construct();
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