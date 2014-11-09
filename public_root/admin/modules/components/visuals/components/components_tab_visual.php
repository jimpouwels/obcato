<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'modules/components/visuals/components/components_list_visual.php';
    require_once CMS_ROOT . 'modules/components/visuals/components/components_details_visual.php';

    class ComponentsTabVisual extends Visual {

        private static $TEMPLATE = 'components/root.tpl';
        private $_template_engine;
        private $_component_request_handler;

        public function __construct($component_request_handler) {
            $this->_component_request_handler = $component_request_handler;
            $this->_template_engine = TemplateEngine::getInstance();
        }

        public function render() {
            $list = new ComponentsListVisual($this->_component_request_handler);
            $details = new ComponentsDetailsVisual($this->_component_request_handler);
            $this->_template_engine->assign('list', $list->render());
            $this->_template_engine->assign('details', $details->render());
            return $this->_template_engine->fetch('modules/components/' . self::$TEMPLATE);
        }
    }