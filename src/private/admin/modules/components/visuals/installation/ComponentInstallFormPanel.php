<?php

namespace Obcato\Core;

use Obcato\ComponentApi\TemplateData;
use Obcato\ComponentApi\TemplateEngine;

class ComponentInstallFormPanel extends Panel {

    public function __construct(TemplateEngine $templateEngine) {
        parent::__construct($templateEngine, 'Installeer component', 'install-form-panel');
    }

    public function getPanelContentTemplate(): string {
        return 'modules/components/installation/component_install_form.tpl';
    }

    public function loadPanelContent(TemplateData $data): void {
        $data->assign('upload_field', $this->renderUploadField());
    }

    private function renderUploadField(): string {
        $uploadField = new UploadField($this->getTemplateEngine(), 'upload_field', 'Upload component', true, "");
        return $uploadField->render();
    }
}
