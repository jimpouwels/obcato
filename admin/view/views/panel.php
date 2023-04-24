<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/views/visual.php";

    abstract class Panel extends Visual {

        private string $_title;
        private string $_html_content;
        private string $_class;

        public function __construct(string $title, string $class = "") {
            parent::__construct();
            $this->_title = $title;
            $this->_class = $class;
        }

        public function getTemplateFilename(): string {
            return "system/panel.tpl";
        }

        abstract function getPanelContentTemplate(): string;

        abstract function loadPanelContent(Smarty_Internal_Data $data): void;

        public function load(): void {
            $child_data = $this->getTemplateEngine()->createChildData();
            $this->loadPanelContent($child_data);
            $this->assign('content', $this->getTemplateEngine()->fetch($this->getPanelContentTemplate(), $child_data));
            $this->assign('panel_title', $this->_title);
            $this->assign('class', $this->_class);
        }

    }
