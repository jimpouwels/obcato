<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/page.php";
    require_once CMS_ROOT . "view/views/module_visual.php";
    require_once CMS_ROOT . "modules/pages/visuals/page_tree.php";
    require_once CMS_ROOT . "modules/pages/visuals/page_editor.php";
    require_once CMS_ROOT . "modules/pages/page_request_handler.php";
    require_once CMS_ROOT . 'database/dao/page_dao.php';

    class PageModuleVisual extends ModuleVisual {

        private static string $HEAD_INCLUDES_TEMPLATE = "modules/pages/head_includes.tpl";
        private ?Page $_current_page;
        private Module $_page_module;
        private PageRequestHandler $_page_request_handler;
        private PageDao $_page_dao;

        public function __construct(Module $page_module) {
            parent::__construct($page_module);
            $this->_page_module = $page_module;
            $this->_page_request_handler = new PageRequestHandler();
            $this->_page_dao = PageDao::getInstance();
        }

        public function getTemplateFilename(): string {
            return "modules/pages/root.tpl";
        }

        public function load(): void {
            $page_tree = new PageTree($this->_page_dao->getRootPage(), $this->_current_page);
            $page_editor = new PageEditor($this->_current_page);
            $this->assign("tree", $page_tree->render());
            $this->assign("editor", $page_editor->render());
        }

        public function getActionButtons(): array {
            $buttons = array();
            $buttons[] = new ActionButtonSave('update_element_holder');
            if (!$this->currentPageIsHomepage()) {
                $buttons[] = new ActionButtonDelete('delete_element_holder');
            }
            $buttons[] = new ActionButtonAdd('add_element_holder');
            if ($this->_current_page->getId() != 1) {
                if (!$this->_current_page->isFirst()) {
                    $buttons[] = new ActionButtonUp('moveup_element_holder');
                }
                if (!$this->_current_page->isLast()) {
                    $buttons[] = new ActionButtonDown('movedown_element_holder');
                }
            }
            return $buttons;
        }

        public function renderHeadIncludes(): string {
            $this->getTemplateEngine()->assign("path", $this->_page_module->getIdentifier());
            $element_statics_values = array();
            $element_statics = $this->_current_page->getElementStatics();
            foreach ($element_statics as $element_static) {
                $element_statics_values[] = $element_static->render();
            }
            $this->getTemplateEngine()->assign("element_statics", $element_statics_values);
            return $this->getTemplateEngine()->fetch(self::$HEAD_INCLUDES_TEMPLATE);
        }

        public function getRequestHandlers(): array {
            $request_handlers = array();
            $request_handlers[] = $this->_page_request_handler;
            return $request_handlers;
        }

        public function onRequestHandled(): void {
            $this->_current_page = $this->_page_request_handler->getCurrentPage();
        }

        public function getTabMenu(): ?TabMenu {
            return null;
        }

        private function currentPageIsHomepage() {
            return !is_null($this->_current_page) && $this->_current_page->getId() == 1;
        }

    }
