<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/views/module_visual.php";
    require_once CMS_ROOT . "modules/templates/template_pre_handler.php";
    require_once CMS_ROOT . "modules/templates/visuals/template_list.php";
    require_once CMS_ROOT . "modules/templates/visuals/template_editor.php";
    require_once CMS_ROOT . "modules/templates/visuals/template_code_viewer.php";

    class TemplateModuleVisual extends ModuleVisual {

        private static $TEMPLATE_MODULE_TEMPLATE = "modules/templates/root.tpl";
        private static $HEAD_INCLUDES_TEMPLATE = "templates/head_includes.tpl";

        private $_template_module;
        private $_template_engine;
        private $_template_pre_handler;
        private $_current_template;
        private $_current_scope;

        public function __construct($template_module) {
            parent::__construct($template_module);
            $this->_template_module = $template_module;
            $this->_template_engine = TemplateEngine::getInstance();
            $this->_template_pre_handler = new TemplatePreHandler();
        }

        public function render(): string {
            $this->_template_engine->assign("current_template_id", $this->getCurrentTemplateId());
            if (!is_null($this->_current_template)) {
                $this->_template_engine->assign("template_editor", $this->renderTemplateEditor());
                $this->_template_engine->assign('template_code_viewer', $this->renderTemplateCodeViewer());
            }
            $this->_template_engine->assign("scope_selector", $this->getScopeSelector());
            if (!is_null($this->_current_scope))
                $this->_template_engine->assign("template_list", $this->renderTemplateList());
            return $this->_template_engine->fetch(self::$TEMPLATE_MODULE_TEMPLATE);
        }

        public function getActionButtons() {
            $action_buttons = array();
            if (!is_null($this->_current_template))
                $action_buttons[] = new ActionButtonSave('update_template');
            $action_buttons[] = new ActionButtonAdd('add_template');
            if (!is_null($this->_current_scope))
                $action_buttons[] = new ActionButtonDelete('delete_template');
            return $action_buttons;
        }

        public function getHeadIncludes() {
            $this->_template_engine->assign("path", $this->_template_module->getIdentifier());
            return $this->_template_engine->fetch("modules/" . self::$HEAD_INCLUDES_TEMPLATE);
        }

        public function getRequestHandlers() {
            $request_handlers = array();
            $request_handlers[] = $this->_template_pre_handler;
            return $request_handlers;
        }

        public function onPreHandled() {
            $this->_current_template = $this->_template_pre_handler->getCurrentTemplate();
            $this->_current_scope = $this->_template_pre_handler->getCurrentScope();
        }

        public function getTitle() {
            return $this->getTextResource($this->_template_module->getTitleTextResourceIdentifier());
        }

        private function getScopeSelector() {
            $scope_selector = new ScopeSelector();
            return $scope_selector->render();
        }

        private function renderTemplateEditor() {
            $template_editor = new TemplateEditor($this->_current_template);
            return $template_editor->render();
        }

        private function renderTemplateCodeViewer() {
            $template_code_viewer = new TemplateCodeViewer($this->_current_template);
            return $template_code_viewer->render();
        }

        private function renderTemplateList() {
            $template_list = new TemplateList($this->_current_scope);
            return $template_list->render();
        }

        private function getCurrentTemplateId() {
            $current_template_id = null;
            if (!is_null($this->_current_template)) {
                $current_template_id = $this->_current_template->getId();
            }
            return $current_template_id;
        }

    }

?>
