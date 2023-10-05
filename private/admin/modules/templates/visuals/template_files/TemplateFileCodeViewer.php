<?php
require_once CMS_ROOT . "/database/dao/ScopeDaoMysql.php";

class TemplateFileCodeViewer extends Panel {

    private TemplateFile $_template_file;

    public function __construct(TemplateFile $template_file) {
        parent::__construct('Markup', 'template_content_panel');
        $this->_template_file = $template_file;
    }

    public function getPanelContentTemplate(): string {
        return "modules/templates/template_code_viewer.tpl";
    }

    public function loadPanelContent(Smarty_Internal_Data $data): void {
        $data->assign('file_content', $this->getTemplateCode());
    }

    private function getTemplateCode(): ?string {
        return htmlspecialchars($this->_template_file->getCode());
    }

}
