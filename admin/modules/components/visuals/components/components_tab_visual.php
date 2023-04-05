<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'modules/components/visuals/components/modules_list_panel.php';
    require_once CMS_ROOT . 'modules/components/visuals/components/elements_list_panel.php';
    require_once CMS_ROOT . 'modules/components/visuals/components/component_details_panel.php';

    class ComponentsTabVisual extends Visual {

        private static $TEMPLATE = 'components/root.tpl';
        private $_template_engine;
        private $_component_request_handler;

        public function __construct($component_request_handler) {
            $this->_component_request_handler = $component_request_handler;
            $this->_template_engine = TemplateEngine::getInstance();
        }

        public function render(): string {
            $modules_list = new ModulesListPanel($this->_component_request_handler);
            $elements_list = new ElementsListPanel($this->_component_request_handler);
            $details = new ComponentsDetailsPanel($this->_component_request_handler);
            $this->_template_engine->assign('modules_list', $modules_list->render());
            $this->_template_engine->assign('elements_list', $elements_list->render());
            $this->_template_engine->assign('details', $details->render());
            return $this->_template_engine->fetch('modules/components/' . self::$TEMPLATE);
        }
    }
