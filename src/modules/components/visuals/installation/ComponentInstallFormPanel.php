<?php

namespace Obcato\Core\modules\components\visuals\installation;

use Obcato\Core\view\TemplateData;
use Obcato\Core\view\views\Panel;
use Obcato\Core\view\views\UploadField;

class ComponentInstallFormPanel extends Panel {

    public function __construct() {
        parent::__construct('Installeer component', 'install-form-panel');
    }

    public function getPanelContentTemplate(): string {
        return 'modules/components/installation/component_install_form.tpl';
    }

    public function loadPanelContent(TemplateData $data): void {
        $data->assign('upload_field', $this->renderUploadField());
    }

    private function renderUploadField(): string {
        $uploadField = new UploadField('upload_field', 'Upload component', true, "");
        return $uploadField->render();
    }
}
