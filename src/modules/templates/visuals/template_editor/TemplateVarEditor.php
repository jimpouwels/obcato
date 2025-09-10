<?php

namespace Obcato\Core\modules\templates\visuals\template_editor;

use Obcato\Core\modules\templates\model\Template;
use Obcato\Core\modules\templates\service\TemplateInteractor;
use Obcato\Core\modules\templates\service\TemplateService;
use Obcato\Core\view\TemplateData;
use Obcato\Core\view\views\Panel;
use Obcato\Core\view\views\TextField;

class TemplateVarEditor extends Panel {

    private Template $template;
    private TemplateService $templateService;

    public function __construct(Template $template) {
        parent::__construct($this->getTextResource('template_var_editor_panel_title'), 'template_editor_panel');
        $this->template = $template;
        $this->templateService = TemplateInteractor::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return "templates/templates/template_var_editor.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $varFields = array();
        foreach ($this->template->getTemplateVars() as $templateVar) {
            $templateVarId = $templateVar->getId();

            $defaultValue = $this->templateService->getTemplateVarDefByTemplateVar($this->template, $templateVar)->getDefaultValue();
            $postfix = "<strong>Default:</strong> $defaultValue";
            $varField = new TextField("template_var_{$templateVarId}_field", $templateVar->getName(), $templateVar->getValue(), false, false, null, true, $postfix);
            $varFields[] = $varField->render();
        }
        $data->assign("var_fields", $varFields);

    }
}
