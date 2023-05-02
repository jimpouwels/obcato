<?php
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/module_visual.php";
    require_once CMS_ROOT . "modules/forms/webform_request_handler.php";
    require_once CMS_ROOT . "database/dao/webform_dao.php";
    require_once CMS_ROOT . "view/views/tab_menu.php";

    class WebFormsModuleVisual extends ModuleVisual {
    
        private static string $HEAD_INCLUDES_TEMPLATE = "images/head_includes.tpl";
        private static int $FORMS_TAB = 0;
        private WebFormDao $_webform_dao;
        private WebFormRequestHandler $_webform_request_handler;
        private Module $_webform_module;
        
        public function __construct(Module $form_module) {
            parent::__construct($form_module);
            $this->_webform_module = $form_module;
            $this->_webform_dao = WebFormDao::getInstance();
            $this->_webform_request_handler = new WebFormRequestHandler();
        }

        public function getTemplateFilename(): string {
            return "modules/webforms/root.tpl";
        }
        
        public function load(): void {
        }
    
        public function getActionButtons(): array {
            return array();
        }
        
        public function renderHeadIncludes(): string {
            $this->getTemplateEngine()->assign("path", $this->_webform_module->getIdentifier());
            return $this->getTemplateEngine()->fetch("modules/" . self::$HEAD_INCLUDES_TEMPLATE);
        }
        
        public function getRequestHandlers(): array {
            $request_handlers = array();
            $request_handlers[] = $this->_webform_request_handler;
            return $request_handlers;
        }
        
        public function onRequestHandled(): void {
        }
        
        public function getTabMenu(): ?TabMenu {
            $tab_menu = new TabMenu();
            $tab_menu->addItem("webforms_tab_forms", self::$FORMS_TAB, true);
            return $tab_menu;
        }
    
    }
    
?>