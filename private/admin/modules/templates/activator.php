<?php
require_once CMS_ROOT . "/view/views/ModuleVisual.php";
require_once CMS_ROOT . "/view/views/TabMenu.php";
require_once CMS_ROOT . "/modules/templates/visuals/template_editor/TemplateEditorTab.php";
require_once CMS_ROOT . "/modules/templates/visuals/template_files/TemplateFilesTab.php";
require_once CMS_ROOT . "/modules/templates/TemplateEditorRequestHandler.php";
require_once CMS_ROOT . "/modules/templates/TemplateFilesRequestHandler.php";

class TemplateModuleVisual extends ModuleVisual {
    private static int $TEMPLATES_TAB = 0;
    private static int $TEMPLATE_FILES_TAB = 1;
    private static string $HEAD_INCLUDES_TEMPLATE = "templates/head_includes.tpl";

    private ?Template $currentTemplate;
    private ?Scope $currentScope;
    private Module $module;
    private TemplateEditorRequestHandler $templateEditorRequestHandler;
    private TemplateFilesRequestHandler $templateFilesRequestHandler;

    public function __construct(Module $module) {
        parent::__construct($module);
        $this->module = $module;
        $this->templateEditorRequestHandler = new TemplateEditorRequestHandler();
        $this->templateFilesRequestHandler = new TemplateFilesRequestHandler();
    }

    public function getTemplateFilename(): string {
        return "modules/templates/root.tpl";
    }

    public function load(): void {
        if ($this->getCurrentTabId() == self::$TEMPLATES_TAB) {
            $content = new TemplateEditorTab($this->currentTemplate, $this->currentScope);
        } else {
            $content = new TemplateFilesTab($this->templateFilesRequestHandler);
        }
        $this->assign("content", $content->render());
    }

    public function getActionButtons(): array {
        $actionButtons = array();
        if ($this->getCurrentTabId() == self::$TEMPLATES_TAB) {
            if ($this->currentTemplate) {
                $actionButtons[] = new ActionButtonSave('update_template');
            }
            $actionButtons[] = new ActionButtonAdd('add_template');
            if ($this->currentScope) {
                $actionButtons[] = new ActionButtonDelete('delete_template');
            }
        } else if ($this->getCurrentTabId() == self::$TEMPLATE_FILES_TAB) {
            if ($this->templateFilesRequestHandler->getCurrentTemplateFile()) {
                $actionButtons[] = new ActionButtonSave('update_template_file');
            }
            $actionButtons[] = new ActionButtonAdd('add_template_file');
            if ($this->templateFilesRequestHandler->getCurrentTemplateFile()) {
                $actionButtons[] = new ActionButtonReload('reload_template_file');
                $actionButtons[] = new ActionButtonDelete('delete_template_file');
            }
        }
        return $actionButtons;
    }

    public function renderHeadIncludes(): string {
        $this->getTemplateEngine()->assign("path", $this->module->getIdentifier());
        return $this->getTemplateEngine()->fetch("modules/" . self::$HEAD_INCLUDES_TEMPLATE);
    }

    public function getRequestHandlers(): array {
        $requestHandlers = array();
        $requestHandlers[] = $this->templateEditorRequestHandler;
        $requestHandlers[] = $this->templateFilesRequestHandler;
        return $requestHandlers;
    }

    public function onRequestHandled(): void {
        $this->currentTemplate = $this->templateEditorRequestHandler->getCurrentTemplate();
        $this->currentScope = $this->templateEditorRequestHandler->getCurrentScope();
    }

    public function getTitle(): string {
        return $this->getTextResource($this->module->getIdentifier() . '_module_title');
    }

    public function getTabMenu(): ?TabMenu {
        $tabMenu = new TabMenu($this->getCurrentTabId());
        $tabMenu->addItem("templates_tab_menu_templates", self::$TEMPLATES_TAB);
        $tabMenu->addItem("templates_tab_menu_template_files", self::$TEMPLATE_FILES_TAB);
        return $tabMenu;
    }
}