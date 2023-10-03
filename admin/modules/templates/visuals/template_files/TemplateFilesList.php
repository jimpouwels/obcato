<?php
defined('_ACCESS') or die;

class TemplateFilesList extends Panel {
    private TemplateDao $_template_dao;

    public function __construct() {
        parent::__construct('template_files_list_title', 'template_files_list_panel');
        $this->_template_dao = TemplateDaoMysql::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return "modules/templates/template_files/template_files_list.tpl";
    }

    public function loadPanelContent(Smarty_Internal_Data $data): void {
        $data->assign("template_files", $this->getAllTemplateFiles());
    }

    private function getAllTemplateFiles(): array {
        $template_files = array();
        foreach ($this->_template_dao->getTemplateFiles() as $template_file) {
            $template_file_array = array();
            $template_file_array["id"] = $template_file->getId();
            $template_file_array["name"] = $template_file->getName();
            $template_files[] = $template_file_array;
        }
        return $template_files;
    }

}

?>