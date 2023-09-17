<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/views/module_visual.php";
    require_once CMS_ROOT . "view/views/tab_menu.php";
    require_once CMS_ROOT . "modules/templates/visuals/template_editor/template_editor_tab.php";
    require_once CMS_ROOT . "modules/templates/visuals/template_files/template_files_tab.php";
    require_once CMS_ROOT . "modules/templates/template_editor_request_handler.php";
    require_once CMS_ROOT . "modules/templates/template_files_request_handler.php";
    
    class TemplateModuleVisual extends ModuleVisual {
        private static int $TEMPLATES_TAB = 0;
        private static int $TEMPLATE_FILES_TAB = 1;
        private static string $HEAD_INCLUDES_TEMPLATE = "templates/head_includes.tpl";

        private ?Template $_current_template;
        private ?TemplateFile $_current_template_file;
        private ?Scope $_current_scope;
        private Module $_template_module;
        private TemplateEditorRequestHandler $_template_editor_request_handler;
        private TemplateFilesRequestHandler $_template_files_request_handler;

        public function __construct(Module $template_module) {
            parent::__construct($template_module);
            $this->_template_module = $template_module;
            $this->_template_editor_request_handler = new TemplateEditorRequestHandler();
            $this->_template_files_request_handler = new TemplateFilesRequestHandler();
        }

        public function getTemplateFilename(): string {
            return "modules/templates/root.tpl";
        }

        public function load(): void {
            if ($this->getCurrentTabId() == self::$TEMPLATES_TAB) {
                $content = new TemplateEditorTab($this->_current_template, $this->_current_scope);
            } else if ($this->getCurrentTabId() == self::$TEMPLATE_FILES_TAB) {
                $content = new TemplateFilesTab($this->_template_files_request_handler);
            } 
            $this->assign("content", $content->render());
        }

        public function getActionButtons(): array {
            $action_buttons = array();
            if ($this->getCurrentTabId() == self::$TEMPLATES_TAB) {
                if (!is_null($this->_current_template)) {
                    $action_buttons[] = new ActionButtonSave('update_template');
                }
                $action_buttons[] = new ActionButtonAdd('add_template');
                if (!is_null($this->_current_scope)) {
                    $action_buttons[] = new ActionButtonDelete('delete_template');
                }
            } else if ($this->getCurrentTabId() == self::$TEMPLATE_FILES_TAB) {
                $action_buttons[] = new ActionButtonSave('update_template_file');
                $action_buttons[] = new ActionButtonAdd('add_template_file');
                $action_buttons[] = new ActionButtonReload('reload_template_file');
            }
            return $action_buttons;
        }

        public function renderHeadIncludes(): string {
            $this->getTemplateEngine()->assign("path", $this->_template_module->getIdentifier());
            return $this->getTemplateEngine()->fetch("modules/" . self::$HEAD_INCLUDES_TEMPLATE);
        }

        public function getRequestHandlers(): array {
            $request_handlers = array();
            $request_handlers[] = $this->_template_editor_request_handler;
            $request_handlers[] = $this->_template_files_request_handler;
            return $request_handlers;
        }

        public function onRequestHandled(): void {
            $this->_current_template = $this->_template_editor_request_handler->getCurrentTemplate();
            $this->_current_scope = $this->_template_editor_request_handler->getCurrentScope();
            $this->_current_template_file = $this->_template_files_request_handler->getCurrentTemplateFile();
        }

        public function getTitle(): string {
            return $this->getTextResource($this->_template_module->getTitleTextResourceIdentifier());
        }

        public function getTabMenu(): ?TabMenu {
            $tab_menu = new TabMenu($this->getCurrentTabId());
            $tab_menu->addItem("templates_tab_menu_templates", self::$TEMPLATES_TAB);
            $tab_menu->addItem("templates_tab_menu_template_files", self::$TEMPLATE_FILES_TAB);
            return $tab_menu;
        }

        private function getScopeSelector(): string {
            $scope_selector = new ScopeSelector();
            return $scope_selector->render();
        }

        private function renderTemplateEditor(): string {
            $template_editor = new TemplateEditor($this->_current_template);
            return $template_editor->render();
        }

        private function renderTemplateVarEditor(): string {
            $template_var_editor = new TemplateVarEditor($this->_current_template);
            return $template_var_editor->render();
        }

        private function renderTemplateList(): string {
            $template_list = new TemplateList($this->_current_scope);
            return $template_list->render();
        }

        private function getCurrentTemplateId(): ?int {
            $current_template_id = null;
            if (!is_null($this->_current_template)) {
                $current_template_id = $this->_current_template->getId();
            }
            return $current_template_id;
        }

    }

?>
