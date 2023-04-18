<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/page.php";
    require_once CMS_ROOT . "view/views/module_visual.php";
    require_once CMS_ROOT . "modules/pages/visuals/page_tree.php";
    require_once CMS_ROOT . "modules/pages/visuals/page_editor.php";
    require_once CMS_ROOT . "modules/pages/page_pre_handler.php";
    require_once CMS_ROOT . 'database/dao/page_dao.php';

    class PageModuleVisual extends ModuleVisual {

        private static $PAGE_MODULE_TEMPLATE = "modules/pages/root.tpl";
        private static $HEAD_INCLUDES_TEMPLATE = "modules/pages/head_includes.tpl";

        private $_current_page;
        private $_page_module;
        private $_page_pre_handler;
        private $_page_dao;

        public function __construct($page_module) {
            parent::__construct($page_module);
            $this->_page_module = $page_module;
            $this->_page_pre_handler = new PagePreHandler();
            $this->_page_dao = PageDao::getInstance();
        }

        public function render(): string {
            $page_tree = new PageTree($this->_page_dao->getRootPage(), $this->_current_page);
            $page_editor = new PageEditor($this->_current_page);
            $this->getTemplateEngine()->assign("tree", $page_tree->render());
            $this->getTemplateEngine()->assign("editor", $page_editor->render());
            return $this->getTemplateEngine()->fetch(self::$PAGE_MODULE_TEMPLATE);
        }

        public function getActionButtons() {
            $buttons = array();
            $buttons[] = new ActionButtonSave('update_element_holder');
            if (!$this->currentPageIsHomepage())
                $buttons[] = new ActionButtonDelete('delete_element_holder');
            $buttons[] = new ActionButtonAdd('add_element_holder');
            if ($this->_current_page->getId() != 1) {
                if (!$this->_current_page->isFirst())
                    $buttons[] = new ActionButtonUp('moveup_element_holder');
                if (!$this->_current_page->isLast())
                    $buttons[] = new ActionButtonDown('movedown_element_holder');
            }
            return $buttons;
        }

        public function renderHeadIncludes() {
            $this->getTemplateEngine()->assign("path", $this->_page_module->getIdentifier());
            $element_statics_values = array();
            $element_statics = $this->_current_page->getElementStatics();
            foreach ($element_statics as $element_static) {
                $element_statics_values[] = $element_static->render();
            }
            $this->getTemplateEngine()->assign("element_statics", $element_statics_values);
            return $this->getTemplateEngine()->fetch(self::$HEAD_INCLUDES_TEMPLATE);
        }

        public function getRequestHandlers() {
            $request_handlers = array();
            $request_handlers[] = $this->_page_pre_handler;
            return $request_handlers;
        }

        public function onRequestHandled(): void {
            $this->_current_page = $this->_page_pre_handler->getCurrentPage();
        }

        private function currentPageIsHomepage() {
            return !is_null($this->_current_page) && $this->_current_page->getId() == 1;
        }

    }
