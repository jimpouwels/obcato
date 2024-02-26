<?php

namespace Obcato\Core\modules\templates\visuals\template_files;

use Obcato\Core\database\dao\TemplateDao;
use Obcato\Core\database\dao\TemplateDaoMysql;
use Obcato\Core\modules\templates\TemplateFilesRequestHandler;
use Obcato\Core\utilities\Arrays;
use Obcato\Core\view\TemplateData;
use Obcato\Core\view\views\Panel;
use Obcato\Core\view\views\TextField;

class TemplateVarMigration extends Panel {
    private TemplateDao $templateDao;
    private TemplateFilesRequestHandler $requestHandler;

    public function __construct(TemplateFilesRequestHandler $requestHandler) {
        parent::__construct('template_var_migration_panel_title', 'template_var_migration_panel');
        $this->templateDao = TemplateDaoMysql::getInstance();
        $this->requestHandler = $requestHandler;
    }

    public function getPanelContentTemplate(): string {
        return "modules/templates/template_files/template_var_migration.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $currentTemplateFile = $this->requestHandler->getCurrentTemplateFile();
        $templatesForFile = array();
        foreach ($this->templateDao->getTemplatesForTemplateFile($currentTemplateFile) as $template) {
            $templateForFile = array();
            $templateForFile['name'] = $template->getName();

            $existingTemplateVars = array();
            foreach ($this->templateDao->getTemplateVars($template) as $templateVar) {
                $existingTemplateVar = array();
                $existingTemplateVar['name'] = $templateVar->getName();
                $existingTemplateVar['value'] = $templateVar->getValue();

                $existingTemplateVar['deleted'] = false;
                if (!Arrays::firstMatch($this->requestHandler->getParsedVarDefs(), fn($parsedVar) => $templateVar->getName() == $parsedVar)) {
                    $existingTemplateVar['deleted'] = true;
                }

                $existingTemplateVars[] = $existingTemplateVar;
            }
            $templateForFile['vars'] = $existingTemplateVars;

            $newVarsField = array();
            foreach ($this->requestHandler->getParsedVarDefs() as $parsedVar) {
                if (!Arrays::firstMatch($template->getTemplateVars(), fn($template_var) => $template_var->getName() == $parsedVar)) {
                    $templateId = $template->getId();
                    $newVarTextfield = new TextField("new_var_$templateId|{$parsedVar}", $parsedVar, "", false, false, null);
                    $newVarsField[] = $newVarTextfield->render();
                }
            }
            $templateForFile['new_vars'] = $newVarsField;
            $templatesForFile[] = $templateForFile;

        }
        $data->assign("templates", $templatesForFile);
    }

}