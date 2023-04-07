<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/views/visual.php";

    abstract class Panel extends Visual {

        private static $TEMPLATE = "system/panel.tpl";
        private $_title;
        private $_html_content;
        private $_class;

        public function __construct($title, $class = "") {
            parent::__construct();
            $this->_title = $title;
            $this->_class = $class;
        }

        public function renderVisual(): string {
            $this->getTemplateEngine()->assign('content', $this->renderPanelContent());
            $this->getTemplateEngine()->assign('panel_title', $this->_title);
            $this->getTemplateEngine()->assign('class', $this->_class);
            return $this->getTemplateEngine()->fetch(self::$TEMPLATE);
        }

        abstract function renderPanelContent();
    }
