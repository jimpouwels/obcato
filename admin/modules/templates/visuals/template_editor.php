<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "database/dao/scope_dao.php";

    class TemplateEditor extends Panel {

        private static string $TEMPLATE_EDITOR_TEMPLATE = "templates/template_editor.tpl";
        private Template $_template;
        private ScopeDao $_scope_dao;

        public function __construct(Template $template) {
            parent::__construct('Template bewerken', 'template_editor_fieldset');
            $this->_template = $template;
            $this->_scope_dao = ScopeDao::getInstance();
        }

        public function render(): string {
            return parent::render();
        }

        public function renderPanelContent(): string {
            $this->getTemplateEngine()->assign("template_id", $this->_template->getId());
            $this->assignEditFields();
            return $this->getTemplateEngine()->fetch("modules/" . self::$TEMPLATE_EDITOR_TEMPLATE);
        }

        private function assignEditFields(): void {
            $name_field = new TextField("name", "Naam", $this->_template->getName(), true, false, null);
            $this->getTemplateEngine()->assign("name_field", $name_field->render());
            $filename_field = new TextField("file_name", "Bestandsnaam", $this->_template->getFileName(), false, false, null);
            $this->getTemplateEngine()->assign("filename_field", $filename_field->render());
            $upload_field = new UploadField("template_file", "Template", false, "");
            $this->getTemplateEngine()->assign("upload_field", $upload_field->render());
            $this->getTemplateEngine()->assign("scopes_field", $this->renderScopesField());
        }

        private function renderScopesField(): string {
            $scopes_identifier_value_pair = array();
            foreach ($this->_scope_dao->getScopes() as $scope) {
                array_push($scopes_identifier_value_pair, array("name" => $scope->getIdentifier(), "value" => $scope->getId()));
            }
            $current_scope = $this->_template->getScope();
            $scopes_field = new PullDown("scope", "Scope", (is_null($current_scope) ? null : $current_scope->getId()), $scopes_identifier_value_pair, 200, true);
            return $scopes_field->render();
        }

    }
