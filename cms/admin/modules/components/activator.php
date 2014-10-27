<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . '/view/views/module_visual.php';
    require_once CMS_ROOT . '/modules/components/visuals/component_install_form_visual.php';
    require_once CMS_ROOT . '/modules/components/install_request_handler.php';

    class ComponentsModuleVisual extends ModuleVisual {

        private static $TEMPLATE = "components/root.tpl";
        private static $HEAD_INCLUDES_TEMPLATE = "components/head_includes.tpl";
        private $_module;
        private $_template_engine;
        private $_install_request_handler;

        public function __construct($components_module) {
            $this->_module = $components_module;
            $this->_template_engine = TemplateEngine::getInstance();
            $this->_install_request_handler = new InstallRequestHandler();
        }

        public function render() {
            $this->_template_engine->assign("component_install_form", $this->renderComponentInstallForm());
            return $this->_template_engine->fetch("modules/" . self::$TEMPLATE);
        }

        public function getHeadIncludes() {
            return $this->_template_engine->fetch("modules/" . self::$HEAD_INCLUDES_TEMPLATE);
        }

        public function getRequestHandlers() {
            $request_handlers = array();
            $request_handlers[] = $this->_install_request_handler;
            return $request_handlers;
        }

        public function onPreHandled() {
        }

        public function getTitle() {
            return $this->_module->getTitle();
        }

        public function getActionButtons() {
            $action_buttons = array();
            $action_buttons[] = new ActionButton("Opslaan", "upload_component", "icon_apply");
            return $action_buttons;
        }

        private function renderComponentInstallForm() {
            $component_install_form = new ComponentInstallFormVisual($this->_install_request_handler);
            return $component_install_form->render();
        }
    }