<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/modules/templates/visuals/template_editor/TemplateList.php";
require_once CMS_ROOT . "/modules/templates/visuals/template_editor/TemplateEditor.php";
require_once CMS_ROOT . "/modules/templates/visuals/template_editor/TemplateVarEditor.php";

class TemplateEditorTab extends Visual {

    private ?Template $_current_template;
    private ?Scope $_current_scope;

    public function __construct(?Template $current_template, ?Scope $current_scope) {
        parent::__construct();
        $this->_current_template = $current_template;
        $this->_current_scope = $current_scope;
    }

    public function getTemplateFilename(): string {
        return "modules/templates/template_editor_tab.tpl";
    }

    public function load(): void {
        $this->assign("current_template_id", $this->getCurrentTemplateId());
        if (!is_null($this->_current_template)) {
            $this->assign("template_editor", $this->renderTemplateEditor());
            $this->assign("template_var_editor", $this->renderTemplateVarEditor());
        }
        $this->assign("scope_selector", $this->getScopeSelector());
        if (!is_null($this->_current_scope)) {
            $this->assign("template_list", $this->renderTemplateList());
        }
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