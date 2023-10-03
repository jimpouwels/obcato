<?php

defined("_ACCESS") or die;

require_once CMS_ROOT . "/core/form/Form.php";
require_once CMS_ROOT . "/database/dao/TemplateDaoMysql.php";

class TemplateEditorForm extends Form {
    private Template $_template;
    private TemplateDao $_template_dao;

    public function __construct(Template $template) {
        $this->_template = $template;
        $this->_template_dao = TemplateDaoMysql::getInstance();
    }

    public function loadFields(): void {
        $this->_template->setName($this->getMandatoryFieldValue("name"));
        $this->_template->setScopeId($this->getMandatoryFieldValue("scope"));

        $new_template_file_id = $this->getFieldValue("template_editor_template_file");
        if ($new_template_file_id != $this->_template->getTemplateFileId()) {
            foreach ($this->_template->getTemplateVars() as $template_var) {
                $this->_template_dao->deleteTemplateVar($template_var);
            }
            $this->_template->setTemplateVars([]);
        }

        if ($new_template_file_id) {
            $this->_template->setTemplateFileId(intval($new_template_file_id));

            foreach ($this->_template_dao->getTemplateFile($this->_template->getTemplateFileId())->getTemplateVarDefs() as $var_def) {
                if (!Arrays::firstMatch($this->_template->getTemplateVars(), function ($tv) use ($var_def) {
                    return $var_def->getName() == $tv->getName();
                })) {
                    $template_var = $this->_template_dao->storeTemplateVar($this->_template, $var_def->getName());
                    $this->_template->addTemplateVar($template_var);
                }
            }
        }

        if ($this->hasErrors()) {
            throw new FormException();
        }
        foreach ($this->_template->getTemplateVars() as $template_var) {
            $template_var_id = $template_var->getId();
            $template_var->setValue($this->getFieldValue("template_var_{$template_var_id}_field"));
            $this->_template_dao->updateTemplateVar($template_var);
        }
    }

}
    