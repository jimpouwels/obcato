<?php
defined('_ACCESS') or die;

class TemplateVarMigration extends Panel {
    private TemplateDao $_template_dao;
    private TemplateFilesRequestHandler $_request_handler;

    public function __construct(TemplateFilesRequestHandler $request_handler) {
        parent::__construct('template_var_migration_panel_title', 'template_var_migration_panel');
        $this->_template_dao = TemplateDaoMysql::getInstance();
        $this->_request_handler = $request_handler;
    }

    public function getPanelContentTemplate(): string {
        return "modules/templates/template_files/template_var_migration.tpl";
    }

    public function loadPanelContent(Smarty_Internal_Data $data): void {
        $current_template_file = $this->_request_handler->getCurrentTemplateFile();
        $templates_for_file = array();
        foreach ($this->_template_dao->getTemplatesForTemplateFile($current_template_file) as $template) {
            $template_for_file = array();
            $template_for_file['name'] = $template->getName();

            $existing_template_vars = array();
            foreach ($this->_template_dao->getTemplateVars($template) as $template_var) {
                $existing_template_var = array();
                $existing_template_var['name'] = $template_var->getName();
                $existing_template_var['value'] = $template_var->getValue();

                $existing_template_var['deleted'] = false;
                if (!Arrays::firstMatch($this->_request_handler->getParsedVarDefs(), function ($parsed_var) use ($template_var) {
                    return $template_var->getName() == $parsed_var;
                })) {
                    $existing_template_var['deleted'] = true;
                }

                $existing_template_vars[] = $existing_template_var;
            }
            $template_for_file['vars'] = $existing_template_vars;

            $new_vars_fields = array();
            foreach ($this->_request_handler->getParsedVarDefs() as $parsed_var) {
                if (!Arrays::firstMatch($template->getTemplateVars(), function ($template_var) use ($parsed_var) {
                    return $template_var->getName() == $parsed_var;
                })) {
                    $new_var_textfield = new TextField("new_var|{$parsed_var}", $parsed_var, "", false, false, null);
                    $new_vars_fields[] = $new_var_textfield->render();
                }
            }
            $template_for_file['new_vars'] = $new_vars_fields;
            $templates_for_file[] = $template_for_file;

        }
        $data->assign("templates", $templates_for_file);
    }

}

?>