<?php
require_once CMS_ROOT . "/modules/templates/service/TemplateInteractor.php";

class TemplateVarEditor extends Panel {

    private Template $template;
    private TemplateService $templateService;

    public function __construct(Template $template) {
        parent::__construct($this->getTextResource('template_var_editor_panel_title'), 'template_editor_panel');
        $this->template = $template;
        $this->templateService = TemplateInteractor::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return "modules/templates/template_var_editor.tpl";
    }

    public function loadPanelContent(Smarty_Internal_Data $data): void {
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
