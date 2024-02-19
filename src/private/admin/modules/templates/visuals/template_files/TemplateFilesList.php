<?php

class TemplateFilesList extends Panel {
    private TemplateDao $templateDao;

    public function __construct(TemplateEngine $templateEngine) {
        parent::__construct($templateEngine, 'template_files_list_title', 'template_files_list_panel');
        $this->templateDao = TemplateDaoMysql::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return "modules/templates/template_files/template_files_list.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $data->assign("template_files", $this->getAllTemplateFiles());
    }

    private function getAllTemplateFiles(): array {
        $template_files = array();
        foreach ($this->templateDao->getTemplateFiles() as $templateFile) {
            $templateFileArray = array();
            $templateFileArray["id"] = $templateFile->getId();
            $templateFileArray["name"] = $templateFile->getName();
            $template_files[] = $templateFileArray;
        }
        return $template_files;
    }

}