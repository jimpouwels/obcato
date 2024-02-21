<?php

namespace Obcato\Core\admin\modules\templates\visuals\template_files;

use Obcato\ComponentApi\TemplateData;
use Obcato\Core\admin\database\dao\TemplateDao;
use Obcato\Core\admin\database\dao\TemplateDaoMysql;
use Obcato\Core\admin\modules\templates\model\TemplateFile;
use Obcato\Core\admin\view\views\Panel;
use Obcato\Core\admin\view\views\TextField;

class TemplateFileEditor extends Panel {
    private TemplateDao $templateDao;
    private TemplateFile $currentTemplateFile;

    public function __construct(TemplateFile $currentTemplateFile) {
        parent::__construct('template_file_editor_panel_title', 'template_file_editor_panel');
        $this->templateDao = TemplateDaoMysql::getInstance();
        $this->currentTemplateFile = $currentTemplateFile;
    }

    public function getPanelContentTemplate(): string {
        return "modules/templates/template_files/template_file_editor.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $id = $this->currentTemplateFile->getId();
        $data->assign("id", $id);

        $nameField = new TextField("template_file_{$id}_name_field", $this->getTextResource("template_file_editor_name_field"), $this->currentTemplateFile->getName(), true, false, null);
        $data->assign("name_field", $nameField->render());
        $filenameField = new TextField("template_file_{$id}_filename_field", $this->getTextResource("template_file_editor_filename_field"), $this->currentTemplateFile->getFileName(), false, false, null);
        $data->assign("filename_field", $filenameField->render());

        $varDefFields = array();
        foreach ($this->templateDao->getTemplateVarDefs($this->currentTemplateFile) as $template_var_def) {
            $varDefId = $template_var_def->getId();
            $varDefField = new TextField("var_def_{$varDefId}_default_value_field", $template_var_def->getName(), $template_var_def->getDefaultValue(), false, false, null);
            $varDefFields[] = $varDefField->render();
        }
        $data->assign("var_defs", $varDefFields);
    }

}