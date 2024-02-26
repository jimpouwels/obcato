<?php

namespace Obcato\Core\modules\templates\visuals\template_editor;

use Obcato\Core\database\dao\ScopeDao;
use Obcato\Core\database\dao\ScopeDaoMysql;
use Obcato\Core\database\dao\TemplateDao;
use Obcato\Core\database\dao\TemplateDaoMysql;
use Obcato\Core\modules\templates\model\Template;
use Obcato\Core\view\TemplateData;
use Obcato\Core\view\views\Panel;
use Obcato\Core\view\views\Pulldown;
use Obcato\Core\view\views\TextField;

class TemplateEditor extends Panel {

    private Template $template;
    private ScopeDao $scopeDao;
    private TemplateDao $templateDao;

    public function __construct(Template $template) {
        parent::__construct('Template bewerken', 'template_editor_panel');
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
        $name_field = new TextField("name", "template_editor_name_field", $this->template->getName(), true, false, null);
        $data->assign("name_field", $name_field->render());
        $data->assign("scopes_field", $this->renderScopesField());

        $templateFileSelect = new Pulldown("template_editor_template_file", $this->getTextResource('template_editor_template_file_field'), $this->template->getTemplateFileId(), $this->getTemplateFilesData(), false, null, true);
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
        $scopesField = new Pulldown("scope", "Scope", $currentScope->getId(), $scopesIdentifierValuePair, 200, true);
        return $scopesField->render();
    }

}