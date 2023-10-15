<?php

abstract class Panel extends Visual {

    private string $_title_resource_identifier;
    private string $_class;

    public function __construct(string $title_resource_identifier, string $class = "") {
        parent::__construct();
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
