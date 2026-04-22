<?php

namespace Pageflow\Core\modules\components\visuals\installation;

use Pageflow\Core\view\TemplateData;
use Pageflow\Core\view\views\Panel;
use Pageflow\Core\view\views\UploadField;

class ComponentInstallFormPanel extends Panel {

    public function __construct() {
        parent::__construct('Installeer component', 'install-form-panel');
    }

    public function getPanelContentTemplate(): string {
        return 'components/templates/installation/component_install_form.tpl';
    }

    public function loadPanelContent(TemplateData $data): void {
        $data->assign('upload_field', $this->renderUploadField());
    }

    private function renderUploadField(): string {
        $uploadField = new UploadField('upload_field', 'Upload component', true, "");
        return $uploadField->render();
    }
}
