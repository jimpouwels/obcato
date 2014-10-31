<?php
    defined('_ACCESS') or die;

    class ComponentsTabVisual extends Visual {

        private static $TEMPLATE = 'components/root.tpl';
        private $_template_engine;

        public function __construct() {
            $this->_template_engine = TemplateEngine::getInstance();
        }

        public function render() {
            return $this->_template_engine->fetch('modules/components/' . self::$TEMPLATE);
        }
    }