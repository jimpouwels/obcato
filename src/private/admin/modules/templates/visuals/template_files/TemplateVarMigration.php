<?php

namespace Obcato\Core;

use Obcato\ComponentApi\TemplateData;
use Obcato\ComponentApi\TemplateEngine;

class TemplateVarMigration extends Panel {
    private TemplateDao $templateDao;
    private TemplateFilesRequestHandler $requestHandler;

    public function __construct(TemplateEngine $templateEngine, TemplateFilesRequestHandler $requestHandler) {
        parent::__construct($templateEngine, 'template_var_migration_panel_title', 'template_var_migration_panel');
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
                    $newVarTextfield = new TextField($this->getTemplateEngine(), "new_var_$templateId|{$parsedVar}", $parsedVar, "", false, false, null);
                    $newVarsField[] = $newVarTextfield->render();
                }
            }
            $templateForFile['new_vars'] = $newVarsField;
            $templatesForFile[] = $templateForFile;

        }
        $data->assign("templates", $templatesForFile);
    }

}