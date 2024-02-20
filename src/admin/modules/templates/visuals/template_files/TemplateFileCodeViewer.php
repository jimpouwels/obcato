<?php

namespace Obcato\Core\admin\modules\templates\visuals\template_files;

use Obcato\ComponentApi\TemplateData;
use Obcato\ComponentApi\TemplateEngine;
use Obcato\Core\admin\modules\templates\model\TemplateFile;
use Obcato\Core\admin\view\views\Panel;

class TemplateFileCodeViewer extends Panel {

    private TemplateFile $templateFile;

    public function __construct(TemplateEngine $templateEngine, TemplateFile $templateFile) {
        parent::__construct($templateEngine, 'Markup', 'template_content_panel');
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
