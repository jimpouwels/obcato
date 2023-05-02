<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "database/dao/scope_dao.php";

    class TemplateEditor extends Panel {

        private Template $_template;
        private ScopeDao $_scope_dao;

        public function __construct(Template $template) {
            parent::__construct('Template bewerken', 'template_editor_panel');
            $this->_template = $template;
            $this->_scope_dao = ScopeDao::getInstance();
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
            $filename_field = new TextField("file_name", "template_editor_filename_field", $this->_template->getFileName(), false, false, null);
            $data->assign("filename_field", $filename_field->render());
            $upload_field = new UploadField("template_file", "template_editor_upload_field", false, "");
            $data->assign("upload_field", $upload_field->render());
            $data->assign("scopes_field", $this->renderScopesField());
        }

        private function renderScopesField(): string {
            $scopes_identifier_value_pair = array();
            foreach ($this->_scope_dao->getScopes() as $scope) {
                array_push($scopes_identifier_value_pair, array("name" => $this->getTextResource($scope->getIdentifier() . '_scope_label'), "value" => $scope->getId()));
            }
            $current_scope = $this->_template->getScope();
            $scopes_field = new PullDown("scope", "Scope", (is_null($current_scope) ? null : $current_scope->getId()), $scopes_identifier_value_pair, 200, true);
            return $scopes_field->render();
        }

    }
