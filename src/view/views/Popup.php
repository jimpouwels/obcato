<?php

namespace Obcato\Core\view\views;

use Obcato\Core\authentication\Session;

class Popup extends Visual {

    private string $popupType;

    public function __construct(string $popupType) {
        parent::__construct();
        $this->popupType = $popupType;
    }

    public function getTemplateFilename(): string {
        return "popup.tpl";
    }

    public function load(): void {
        $content = null;
        if ($this->popupType == "search") {
            $content = new Search();
        }
        $this->assignGlobal("text_resources", Session::getTextResources());
        $this->assign("content", $content->render());
    }

}