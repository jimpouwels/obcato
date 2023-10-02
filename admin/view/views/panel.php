<?php
defined('_ACCESS') or die;

abstract class Panel extends Visual {

    private string $_title_resource_identifier;
    private string $_class;

    public function __construct(string $title_resource_identifier, string $class = "", ?Visual $parent = null) {
        parent::__construct($parent);
        $this->_title_resource_identifier = $title_resource_identifier;
        $this->_class = $class;
    }

    public function getTemplateFilename(): string {
        return "system/panel.tpl";
    }

    abstract function getPanelContentTemplate(): string;

    abstract function loadPanelContent(Smarty_Internal_Data $data): void;

    public function load(): void {
        $panel_content_template_data = $this->getTemplateEngine()->createChildData();
        $this->loadPanelContent($panel_content_template_data);

        $this->assign('content', $this->getTemplateEngine()->fetch($this->getPanelContentTemplate(), $panel_content_template_data));
        $this->assign('title_resource_identifier', $this->_title_resource_identifier);
        $this->assign('class', $this->_class);
    }

}
