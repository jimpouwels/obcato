<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/database/dao/template_dao.php";

class TemplateVarEditor extends Panel {

    private Template $_template;

    public function __construct(Template $template) {
        parent::__construct($this->getTextResource('template_var_editor_panel_title'), 'template_editor_panel');
        $this->_template = $template;
    }

    public function getPanelContentTemplate(): string {
        return "modules/templates/template_var_editor.tpl";
    }

    public function loadPanelContent(Smarty_Internal_Data $data): void {
        $var_fields = array();
        foreach ($this->_template->getTemplateVars() as $template_var) {
            $template_var_id = $template_var->getId();
            $var_field = new TextField("template_var_{$template_var_id}_field", $template_var->getName(), $template_var->getValue(), false, false, null);
            $var_fields[] = $var_field->render();
        }
        $data->assign("var_fields", $var_fields);

    }
}
