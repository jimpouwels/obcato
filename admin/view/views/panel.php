<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/views/visual.php";

    abstract class Panel extends Visual {

        private static $TEMPLATE = "system/panel.tpl";
        private $_title;
        private $_html_content;
        private $_class;
        private $_template_engine;

        public function __construct($title, $class = "") {
            $this->_template_engine = TemplateEngine::getInstance();
            $this->_title = $title;
            $this->_class = $class;
        }

        public function render() {
            $this->_template_engine->assign('content', $this->renderPanelContent());
            $this->_template_engine->assign('panel_title', $this->_title);
            $this->_template_engine->assign('class', $this->_class);
            return $this->_template_engine->fetch(self::$TEMPLATE);
        }

        abstract function renderPanelContent();
    }
