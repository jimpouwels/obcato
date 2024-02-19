<?php

abstract class Panel extends Obcato\ComponentApi\Visual {

    private string $_title_resource_identifier;
    private string $_class;

    public function __construct(TemplateEngine $templateEngine, string $title_resource_identifier, string $class = "") {
        parent::__construct($templateEngine);
        $this->_title_resource_identifier = $title_resource_identifier;
        $this->_class = $class;
    }

    public function getTemplateFilename(): string {
        return "system/panel.tpl";
    }

    abstract function getPanelContentTemplate(): string;

    abstract function loadPanelContent(Smarty_Internal_Data $data): void;

    public function load(): void {
        $panelContentTemplateData = $this->createChildData();
        $this->loadPanelContent($panelContentTemplateData);

        $this->assign('content', $this->fetch($this->getPanelContentTemplate(), $panelContentTemplateData));
        $this->assign('title_resource_identifier', $this->_title_resource_identifier);
        $this->assign('class', $this->_class);
    }

}
