<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/database/dao/ScopeDaoMysql.php";

class TemplateEditor extends Panel {

    private Template $_template;
    private ScopeDao $_scope_dao;
    private TemplateDao $_template_dao;

    public function __construct(Template $template) {
        parent::__construct('Template bewerken', 'template_editor_panel');
        $this->_template = $template;
        $this->_scope_dao = ScopeDaoMysql::getInstance();
        $this->_template_dao = TemplateDaoMysql::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return "modules/templates/template_editor.tpl";
    }

    public function loadPanelContent(Smarty_Internal_Data $data): void {
        $data->assign("template_id", $this->_template->getId());
        $this->assignEditFields($data);
    }

    private function assignEditFields($data): void {
        $name_field = new TextField("name", "template_editor_name_field", $this->_template->getName(), true, false, null);
        $data->assign("name_field", $name_field->render());
        $data->assign("scopes_field", $this->renderScopesField());

        $template_file_select = new Pulldown("template_editor_template_file", $this->getTextResource('template_editor_template_file_field'), $this->_template->getTemplateFileId(), $this->getTemplateFilesData(), false, null, true);
        $data->assign("template_files_selector", $template_file_select->render());
    }

    private function getTemplateFilesData(): array {
        $template_files_data = array();
        foreach ($this->_template_dao->getTemplateFiles() as $template_file) {
            $template_file_data = array();
            $template_file_data['name'] = $template_file->getName();
            $template_file_data['value'] = $template_file->getId();
            $template_files_data[] = $template_file_data;
        }
        return $template_files_data;
    }

    private function renderScopesField(): string {
        $scopes_identifier_value_pair = array();
        foreach ($this->_scope_dao->getScopes() as $scope) {
            $scopes_identifier_value_pair[] = array("name" => $this->getTextResource($scope->getIdentifier() . '_scope_label'), "value" => $scope->getId());
        }
        $current_scope = $this->_template->getScope();
        $scopes_field = new Pulldown("scope", "Scope", $current_scope->getId(), $scopes_identifier_value_pair, 200, true);
        return $scopes_field->render();
    }

}
