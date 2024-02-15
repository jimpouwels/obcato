<?php
require_once CMS_ROOT . '/modules/components/visuals/components/ModulesListPanel.php';
require_once CMS_ROOT . '/modules/components/visuals/components/ElementsListPanel.php';
require_once CMS_ROOT . '/modules/components/visuals/components/ComponentsDetailsPanel.php';

class ComponentsTabVisual extends Visual {

    private ComponentRequestHandler $componentRequestHandler;

    public function __construct($component_requestHandler) {
        parent::__construct();
        $this->componentRequestHandler = $component_requestHandler;
    }

    public function getTemplateFilename(): string {
        return 'modules/components/root.tpl';
    }

    public function load(): void {
        $modules_list = new ModulesListPanel($this->componentRequestHandler);
        $elements_list = new ElementsListPanel($this->componentRequestHandler);
        $details = new ComponentsDetailsPanel($this->componentRequestHandler);
        $this->assign('modules_list', $modules_list->render());
        $this->assign('elements_list', $elements_list->render());
        $this->assign('details', $details->render());
    }
}
