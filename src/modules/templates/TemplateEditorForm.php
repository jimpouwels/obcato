<?php

namespace Obcato\Core\modules\templates;

use Obcato\Core\core\form\Form;
use Obcato\Core\core\form\FormException;
use Obcato\Core\database\dao\TemplateDao;
use Obcato\Core\database\dao\TemplateDaoMysql;
use Obcato\Core\modules\templates\model\Template;
use Obcato\Core\utilities\Arrays;

class TemplateEditorForm extends Form {
    private Template $template;
    private TemplateDao $templateDao;

    public function __construct(Template $template) {
        $this->template = $template;
        $this->templateDao = TemplateDaoMysql::getInstance();
    }

    public function loadFields(): void {
        $this->template->setName($this->getMandatoryFieldValue("name"));
        $this->template->setScopeId($this->getMandatoryFieldValue("scope"));

        $newTemplateFileId = $this->getFieldValue("template_editor_template_file");
        if ($newTemplateFileId != $this->template->getTemplateFileId()) {
            foreach ($this->template->getTemplateVars() as $templateVar) {
                $this->templateDao->deleteTemplateVar($templateVar);
            }
            $this->template->setTemplateVars([]);
        }

        if ($newTemplateFileId) {
            $this->template->setTemplateFileId(intval($newTemplateFileId));

            foreach ($this->templateDao->getTemplateFile($this->template->getTemplateFileId())->getTemplateVarDefs() as $varDef) {
                if (!Arrays::firstMatch($this->template->getTemplateVars(), function ($tv) use ($varDef) {
                    return $varDef->getName() == $tv->getName();
                })) {
                    $templateVar = $this->templateDao->storeTemplateVar($this->template, $varDef->getName());
                    $this->template->addTemplateVar($templateVar);
                }
            }
        }

        if ($this->hasErrors()) {
            throw new FormException();
        }
        foreach ($this->template->getTemplateVars() as $templateVar) {
            $templateVarId = $templateVar->getId();
            $templateVar->setValue($this->getFieldValue("template_var_{$templateVarId}_field"));
            $this->templateDao->updateTemplateVar($templateVar);
        }
    }

}
    