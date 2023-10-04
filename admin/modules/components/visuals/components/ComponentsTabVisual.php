<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . '/modules/components/visuals/components/modules_list_panel.php';
require_once CMS_ROOT . '/modules/components/visuals/components/elements_list_panel.php';
require_once CMS_ROOT . '/modules/components/visuals/components/component_details_panel.php';

class ComponentsTabVisual extends Visual {

    private $_component_request_handler;

    public function __construct($component_request_handler) {
        parent::__construct();
        $this->_component_request_handler = $component_request_handler;
    }

    public function getTemplateFilename(): string {
        return 'modules/components/root.tpl';
    }

    public function load(): void {
        $modules_list = new ModulesListPanel($this->_component_request_handler);
        $elements_list = new ElementsListPanel($this->_component_request_handler);
        $details = new ComponentsDetailsPanel($this->_component_request_handler);
        $this->assign('modules_list', $modules_list->render());
        $this->assign('elements_list', $elements_list->render());
        $this->assign('details', $details->render());
    }
}