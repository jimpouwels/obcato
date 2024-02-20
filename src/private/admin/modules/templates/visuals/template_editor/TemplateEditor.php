<?php

namespace Obcato\Core;

use Obcato\ComponentApi\TemplateData;
use Obcato\ComponentApi\TemplateEngine;

class TemplateEditor extends Panel {

    private Template $template;
    private ScopeDao $scopeDao;
    private TemplateDao $templateDao;

    public function __construct(TemplateEngine $templateEngine, Template $template) {
        parent::__construct($templateEngine, 'Template bewerken', 'template_editor_panel');
        $this->template = $template;
        $this->scopeDao = ScopeDaoMysql::getInstance();
        $this->templateDao = TemplateDaoMysql::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return "modules/templates/template_editor.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $data->assign("template_id", $this->template->getId());
        $this->assignEditFields($data);
    }

    private function assignEditFields($data): void {
        $name_field = new TextField($this->getTemplateEngine(), "name", "template_editor_name_field", $this->template->getName(), true, false, null);
        $data->assign("name_field", $name_field->render());
        $data->assign("scopes_field", $this->renderScopesField());

        $templateFileSelect = new Pulldown($this->getTemplateEngine(), "template_editor_template_file", $this->getTextResource('template_editor_template_file_field'), $this->template->getTemplateFileId(), $this->getTemplateFilesData(), false, null, true);
        $data->assign("template_files_selector", $templateFileSelect->render());
    }

    private function getTemplateFilesData(): array {
        $templateFilesData = array();
        foreach ($this->templateDao->getTemplateFiles() as $templateFile) {
            $templateFileData = array();
            $templateFileData['name'] = $templateFile->getName();
            $templateFileData['value'] = $templateFile->getId();
            $templateFilesData[] = $templateFileData;
        }
        return $templateFilesData;
    }

    private function renderScopesField(): string {
        $scopesIdentifierValuePair = array();
        foreach ($this->scopeDao->getScopes() as $scope) {
            $scopesIdentifierValuePair[] = array("name" => $this->getTextResource($scope->getIdentifier() . '_scope_label'), "value" => $scope->getId());
        }
        $currentScope = $this->template->getScope();
        $scopesField = new Pulldown($this->getTemplateEngine(), "scope", "Scope", $currentScope->getId(), $scopesIdentifierValuePair, 200, true);
        return $scopesField->render();
    }

}
