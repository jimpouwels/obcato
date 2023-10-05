<?php
require_once CMS_ROOT . "/core/form/Form.php";
require_once CMS_ROOT . "/database/dao/TemplateDaoMysql.php";

class TemplateFileForm extends Form {

    private TemplateFile $_template_file;
    private TemplateDao $_template_dao;
    private array $_parsed_var_defs = array();

    public function __construct(TemplateFile $template_file) {
        $this->_template_file = $template_file;
        $this->_template_dao = TemplateDaoMysql::getInstance();
    }

    public function getParsedVarDefs(): array {
        return $this->_parsed_var_defs;
    }

    public function loadFields(): void {
        $id = $this->_template_file->getId();
        $this->_template_file->setName($this->getMandatoryFieldValue("template_file_{$id}_name_field"));
        $this->_template_file->setFileName($this->getFieldValue("template_file_{$id}_filename_field"));

        $this->_parsed_var_defs = $this->parseVarDefs();

        foreach ($this->_template_file->getTemplateVarDefs() as $var_def) {
            $var_def_id = $var_def->getId();
            $var_def->setDefaultValue($this->getFieldValue("var_def_{$var_def_id}_default_value_field"));
            $this->_template_dao->updateTemplateVarDef($var_def);
        }

        // update template file
        foreach ($this->_parsed_var_defs as $parsed_var_def) {
            if (!Arrays::firstMatch($this->_template_file->getTemplateVarDefs(), function ($var_def) use ($parsed_var_def) {
                return $var_def->getName() == $parsed_var_def;
            })) {
                $template_var_def = $this->_template_dao->storeTemplateVarDef($this->_template_file, $parsed_var_def);
                $this->_template_file->addTemplateVarDef($template_var_def);
            }
        }
        foreach ($this->_template_file->getTemplateVarDefs() as $template_file_var_def) {
            if (!Arrays::firstMatch($this->_parsed_var_defs, function ($parsed_var_def) use ($template_file_var_def) {
                return $template_file_var_def->getName() == $parsed_var_def;
            })) {
                $this->_template_dao->deleteTemplateVarDef($template_file_var_def);
                $this->_template_file->deleteTemplateVarDef($template_file_var_def);
            }
        }

        // update all templates (migration)
        if (isset($_POST['is_migrating'])) {
            foreach ($this->_template_dao->getTemplatesForTemplateFile($this->_template_file) as $template) {
                foreach ($template->getTemplateVars() as $template_var) {
                    if (!Arrays::firstMatch($this->_parsed_var_defs, function ($parsed_var_def) use ($template_var) {
                        return $template_var->getName() == $parsed_var_def;
                    })) {
                        $this->_template_dao->deleteTemplateVar($template_var);
                        $template->deleteTemplateVar($template_var);
                    }
                }
                foreach ($this->_parsed_var_defs as $parsed_var_def) {
                    if (!Arrays::firstMatch($template->getTemplateVars(), function ($template_var) use ($parsed_var_def) {
                        return $template_var->getName() == $parsed_var_def;
                    })) {
                        $this->_template_dao->storeTemplateVar($template, $parsed_var_def, $this->getFieldValue("new_var|{$parsed_var_def}"));
                    }
                }
            }
        }

    }

    private function parseVarDefs(): array {
        $parsed_var_defs = array();
        $code = $this->_template_file->getCode();
        $matches = null;
        preg_match_all('/\$var\.(.*?)[\ }]/', $code, $matches);

        for ($i = 0; $i < count($matches[1]); $i++) {
            $var_def_name = $matches[1][$i];
            $parsed_var_defs[] = $var_def_name;
        }
        return $parsed_var_defs;
    }

}
    