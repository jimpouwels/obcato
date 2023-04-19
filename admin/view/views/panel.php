<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/views/visual.php";

    abstract class Panel extends Visual {

        private static string $TEMPLATE = "system/panel.tpl";
        private string $_title;
        private string $_html_content;
        private string $_class;

        public function __construct(string $title, string $class = "") {
            parent::__construct();
            $this->_title = $title;
            $this->_class = $class;
        }

        public function render(): string {
            $this->getTemplateEngine()->assign('content', $this->renderPanelContent());
            $this->getTemplateEngine()->assign('panel_title', $this->_title);
            $this->getTemplateEngine()->assign('class', $this->_class);
            return $this->getTemplateEngine()->fetch(self::$TEMPLATE);
        }

        abstract function renderPanelContent();
    }
