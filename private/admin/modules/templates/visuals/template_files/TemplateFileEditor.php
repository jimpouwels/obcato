<?php

class TemplateFileEditor extends Panel {
    private TemplateDao $_template_dao;
    private TemplateFile $_current_template_file;

    public function __construct(TemplateFile $current_template_file) {
        parent::__construct('template_file_editor_panel_title', 'template_file_editor_panel');
        $this->_template_dao = TemplateDaoMysql::getInstance();
        $this->_current_template_file = $current_template_file;
    }

    public function getPanelContentTemplate(): string {
        return "modules/templates/template_files/template_file_editor.tpl";
    }

    public function loadPanelContent(Smarty_Internal_Data $data): void {
        $id = $this->_current_template_file->getId();
        $data->assign("id", $id);

        $name_field = new TextField("template_file_{$id}_name_field", $this->getTextResource("template_file_editor_name_field"), $this->_current_template_file->getName(), true, false, null);
        $data->assign("name_field", $name_field->render());
        $filename_field = new TextField("template_file_{$id}_filename_field", $this->getTextResource("template_file_editor_filename_field"), $this->_current_template_file->getFileName(), false, false, null);
        $data->assign("filename_field", $filename_field->render());

        $var_def_fields = array();
        foreach ($this->_template_dao->getTemplateVarDefs($this->_current_template_file) as $template_var_def) {
            $var_def_id = $template_var_def->getId();
            $var_def_field = new TextField("var_def_{$var_def_id}_default_value_field", $template_var_def->getName(), $template_var_def->getDefaultValue(), false, false, null);
            $var_def_fields[] = $var_def_field->render();
        }
        $data->assign("var_defs", $var_def_fields);
    }

}

?>