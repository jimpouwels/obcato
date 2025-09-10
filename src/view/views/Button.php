<?php

namespace Obcato\Core\view\views;

class Button extends Visual {

    private ?string $id;
    private string $labelResourceIdentifier;
    private ?string $onclick;

    public function __construct(?string $id, string $labelResourceIdentifier, ?string $onclick) {
        parent::__construct();
        $this->id = $id;
        $this->labelResourceIdentifier = $labelResourceIdentifier;
        $this->onclick = $onclick;
    }

    public function getTemplateFilename(): string {
        return "button.tpl";
    }

    public function load(): void {
        $this->assign("id", $this->id);
        $this->assign("label_resource_identifier", $this->labelResourceIdentifier);
        $this->assign("onclick", $this->onclick);
    }

}