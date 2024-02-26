<?php

namespace Obcato\Core\modules\templates\visuals\template_files;

use Obcato\Core\modules\templates\model\TemplateFile;
use Obcato\Core\view\TemplateData;
use Obcato\Core\view\views\Panel;

class TemplateFileCodeViewer extends Panel {

    private TemplateFile $templateFile;

    public function __construct(TemplateFile $templateFile) {
        parent::__construct('Markup', 'template_content_panel');
        $this->templateFile = $templateFile;
    }

    public function getPanelContentTemplate(): string {
        return "modules/templates/template_code_viewer.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $data->assign('file_content', $this->getTemplateCode());
    }

    private function getTemplateCode(): ?string {
        return htmlspecialchars($this->templateFile->getCode());
    }

}
