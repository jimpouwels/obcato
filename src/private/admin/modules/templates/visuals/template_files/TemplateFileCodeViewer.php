<?php
require_once CMS_ROOT . "/database/dao/ScopeDaoMysql.php";

class TemplateFileCodeViewer extends Panel {

    private TemplateFile $templateFile;

    public function __construct(TemplateFile $templateFile) {
        parent::__construct('Markup', 'template_content_panel');
        $this->templateFile = $templateFile;
    }

    public function getPanelContentTemplate(): string {
        return "modules/templates/template_code_viewer.tpl";
    }

    public function loadPanelContent(Smarty_Internal_Data $data): void {
        $data->assign('file_content', $this->getTemplateCode());
    }

    private function getTemplateCode(): ?string {
        return htmlspecialchars($this->templateFile->getCode());
    }

}
