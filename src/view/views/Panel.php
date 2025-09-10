<?php

namespace Obcato\Core\view\views;

use Obcato\Core\view\TemplateData;

abstract class Panel extends Visual {

    private string $titleResrouceIdentifier;
    private string $class;

    public function __construct(string $titleResrouceIdentifier, string $class = "") {
        parent::__construct();
        $this->titleResrouceIdentifier = $titleResrouceIdentifier;
        $this->class = $class;
    }

    public function getTemplateFilename(): string {
        return "panel.tpl";
    }

    abstract function getPanelContentTemplate(): string;

    abstract function loadPanelContent(TemplateData $data): void;

    public function load(): void {
        $panelContentTemplateData = $this->createChildData();
        $this->loadPanelContent($panelContentTemplateData);

        $this->assign('content', $this->fetch($this->getPanelContentTemplate(), $panelContentTemplateData));
        $this->assign('title_resource_identifier', $this->titleResrouceIdentifier);
        $this->assign('class', $this->class);
    }

}
