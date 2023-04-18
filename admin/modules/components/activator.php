<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'view/views/module_visual.php';
    require_once CMS_ROOT . 'view/views/tab_menu.php';
    require_once CMS_ROOT . 'modules/components/visuals/installation/installation_tab_visual.php';
    require_once CMS_ROOT . 'modules/components/visuals/components/components_tab_visual.php';
    require_once CMS_ROOT . 'modules/components/install_request_handler.php';
    require_once CMS_ROOT . 'modules/components/component_request_handler.php';

    class ComponentsModuleVisual extends ModuleVisual {

        private static $TEMPLATE = 'components/root.tpl';
        private static $HEAD_INCLUDES_TEMPLATE = 'components/head_includes.tpl';
        private static $COMPONENTS_TAB = 0;
        private static $INSTALLATION_TAB = 1;
        private $_module;
        private $_install_request_handler;
        private $_component_request_handler;

        public function __construct($components_module) {
            parent::__construct($components_module);
            $this->_module = $components_module;
            $this->_install_request_handler = new InstallRequestHandler();
            $this->_component_request_handler = new ComponentRequestHandler();
        }

        public function render(): string {
            $this->getTemplateEngine()->assign('tab_menu', $this->renderTabMenu());
            if ($this->getCurrentTabId() == self::$COMPONENTS_TAB) {
                $content = new ComponentsTabVisual($this->_component_request_handler);
            } else if ($this->getCurrentTabId() == self::$INSTALLATION_TAB) {
                $content = new InstallationTabVisual($this->_install_request_handler);
            }
            $this->getTemplateEngine()->assign('content', $content->render());
            return $this->getTemplateEngine()->fetch("modules/" . self::$TEMPLATE);
        }

        public function renderHeadIncludes() {
            return $this->getTemplateEngine()->fetch("modules/" . self::$HEAD_INCLUDES_TEMPLATE);
        }

        public function getRequestHandlers() {
            $request_handlers = array();
            if ($this->getCurrentTabId() == self::$COMPONENTS_TAB)
                $request_handlers[] = $this->_component_request_handler;
            if ($this->getCurrentTabId() == self::$INSTALLATION_TAB)
                $request_handlers[] = $this->_install_request_handler;
            return $request_handlers;
        }

        public function onRequestHandled(): void {
        }

        public function getActionButtons() {
            $action_buttons = array();
            if ($this->getCurrentTabId() ==  self::$INSTALLATION_TAB)
                $action_buttons[] = new ActionButtonSave('upload_component');
            if ($this->isCurrentComponentUninstallable())
                $action_buttons[] = new ActionButtonDelete('uninstall_component');
            return $action_buttons;
        }

        private function isCurrentComponentUninstallable() {
            $current_module = $this->_component_request_handler->getCurrentModule();
            if ($current_module && !$current_module->isSystemDefault())
                return true;
            $current_element = $this->_component_request_handler->getCurrentElement();
            if ($current_element && !$current_element->getSystemDefault())
                return true;
            return false;
        }

        private function renderTabMenu() {
            $tab_items = array();
            
            $tab_item = array();
            $tab_item["text"] = "Componenten";
            $tab_item["id"] = self::$COMPONENTS_TAB;
            $tab_items[] = $tab_item;

            $tab_item = array();
            $tab_item["text"] = "Installeren";
            $tab_item["id"] = self::$INSTALLATION_TAB;
            $tab_items[] = $tab_item;

            $tab_menu = new TabMenu($tab_items, $this->getCurrentTabId());
            return $tab_menu->render();
        }
    }