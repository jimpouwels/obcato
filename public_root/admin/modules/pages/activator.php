<?php

    
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "core/data/page.php";
    require_once CMS_ROOT . "view/views/module_visual.php";
    require_once CMS_ROOT . "modules/pages/visuals/page_tree.php";
    require_once CMS_ROOT . "modules/pages/visuals/page_editor.php";
    require_once CMS_ROOT . "modules/pages/page_pre_handler.php";

    class PageModuleVisual extends ModuleVisual {

        private static $PAGE_MODULE_TEMPLATE = "modules/pages/root.tpl";
        private static $HEAD_INCLUDES_TEMPLATE = "modules/pages/head_includes.tpl";
    
        private $_current_page;
        private $_template_engine;
        private $_page_module;
        private $_page_pre_handler;
    
        public function __construct($page_module) {
            $this->_page_module = $page_module;
            $this->_template_engine = TemplateEngine::getInstance();
            $this->_page_pre_handler = new PagePreHandler();
        }
    
        public function render() {
            $page_tree = new PageTree(Settings::find()->getHomepage(), $this->_current_page);
            $page_editor = new PageEditor($this->_current_page);
            
            $this->_template_engine->assign("tree", $page_tree->render());
            $this->_template_engine->assign("editor", $page_editor->render());
            return $this->_template_engine->fetch(self::$PAGE_MODULE_TEMPLATE);
        }
        
        public function getTitle() {
            return $this->getTextResource($this->_page_module->getTitleTextResourceIdentifier());
        }
        
        public function getActionButtons() {
            $buttons = array();
            $buttons[] = new ActionButton("Opslaan", "update_element_holder", "icon_apply");
            if (!is_null($this->_current_page)) {
                if ($this->_current_page->getId() != 1) {
                    $buttons[] = new ActionButton("Verwijderen", "delete_element_holder", "icon_delete");
                }
            }
            $buttons[] = new ActionButton("Toevoegen", "add_element_holder", "icon_add");
            if ($this->_current_page->getId() != 1) {
                if (!$this->_current_page->isFirst()) {
                    $buttons[] = new ActionButton("Omhoog", "moveup_element_holder", "icon_moveup");
                }
                if (!$this->_current_page->isLast()) {
                    $buttons[] = new ActionButton("Omlaag", "movedown_element_holder", "icon_movedown");
                }
            }
            
            return $buttons;
        }
        
        public function getHeadIncludes() {
            $this->_template_engine->assign("path", $this->_page_module->getIdentifier());
            $element_statics_values = array();            
            $element_statics = $this->_current_page->getElementStatics();
            if (count($element_statics) > 0) {
                foreach ($element_statics as $element_static) {
                    $element_statics_values[] = $element_static->render();
                }
            }
            $this->_template_engine->assign("element_statics", $element_statics_values);
            return $this->_template_engine->fetch(self::$HEAD_INCLUDES_TEMPLATE);
        }
        
        public function getRequestHandlers() {
            $request_handlers = array();
            $request_handlers[] = $this->_page_pre_handler;
            return $request_handlers;
        }
        
        public function onPreHandled() {
            $this->_current_page = $this->_page_pre_handler->getCurrentPage();
        }
    
    }
    
?>