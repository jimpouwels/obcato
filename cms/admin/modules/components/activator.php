<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'view/views/module_visual.php';
    require_once CMS_ROOT . 'view/views/tab_menu.php';
    require_once CMS_ROOT . 'modules/components/visuals/installation/installation_tab_visual.php';
    require_once CMS_ROOT . 'modules/components/visuals/components/components_tab_visual.php';
    require_once CMS_ROOT . 'modules/components/install_request_handler.php';

    class ComponentsModuleVisual extends ModuleVisual {

        private static $TEMPLATE = 'components/root.tpl';
        private static $HEAD_INCLUDES_TEMPLATE = 'components/head_includes.tpl';
        private static $COMPONENTS_TAB = 0;
        private static $INSTALLATION_TAB = 1;
        private $_module;
        private $_template_engine;
        private $_install_request_handler;

        public function __construct($components_module) {
            $this->_module = $components_module;
            $this->_template_engine = TemplateEngine::getInstance();
            $this->_install_request_handler = new InstallRequestHandler();
        }

        public function render() {
            $this->_template_engine->assign('tab_menu', $this->renderTabMenu());
            if ($this->getCurrentTabId() == self::$COMPONENTS_TAB)
                $content = new ComponentsTabVisual();
            else if ($this->getCurrentTabId() == self::$INSTALLATION_TAB)
                $content = new InstallationTabVisual($this->_install_request_handler);
            $this->_template_engine->assign('content', $content->render());
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
            if ($this->getCurrentTabId() ==  self::$INSTALLATION_TAB)
                $action_buttons[] = new ActionButton("Opslaan", "upload_component", "icon_apply");
            return $action_buttons;
        }

        private function renderTabMenu() {
            $tab_items = array();
            $tab_items[self::$COMPONENTS_TAB] = "Componenten";
            $tab_items[self::$INSTALLATION_TAB] = "Installeren";
            $tab_menu = new TabMenu($tab_items, $this->getCurrentTabId());
            return $tab_menu->render();
        }

        private function getCurrentTabId() {
            return $this->_install_request_handler->getCurrentTabId();
        }
    }