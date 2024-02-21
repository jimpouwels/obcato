<?php

namespace Obcato\Core\admin\modules\templates\visuals\template_editor;

use Obcato\Core\admin\modules\templates\model\Scope;
use Obcato\Core\admin\modules\templates\model\Template;
use Obcato\Core\admin\view\views\Visual;

class TemplateEditorTab extends Visual {

    private ?Template $currentTemplate;
    private ?Scope $currentScope;

    public function __construct(?Template $current, ?Scope $currentScope) {
        parent::__construct();
        $this->currentTemplate = $current;
        $this->currentScope = $currentScope;
    }

    public function getTemplateFilename(): string {
        return "modules/templates/template_editor_tab.tpl";
    }

    public function load(): void {
        $this->assign("current_template_id", $this->getCurrentTemplateId());
        if (!is_null($this->currentTemplate)) {
            $this->assign("template_editor", $this->renderTemplateEditor());
            $this->assign("template_var_editor", $this->renderTemplateVarEditor());
        }
        $this->assign("scope_selector", $this->getScopeSelector());
        if (!is_null($this->currentScope)) {
            $this->assign("template_list", $this->renderTemplateList());
        }
    }

    private function getScopeSelector(): string {
        return (new ScopeSelector())->render();
    }

    private function renderTemplateEditor(): string {
        return (new TemplateEditor($this->currentTemplate))->render();
    }

    private function renderTemplateVarEditor(): string {
        return (new TemplateVarEditor($this->currentTemplate))->render();
    }

    private function renderTemplateList(): string {
        return (new TemplateList($this->currentScope))->render();
    }

    private function getCurrentTemplateId(): ?int {
        return $this->currentTemplate?->getId();
    }

}