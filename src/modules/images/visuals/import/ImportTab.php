<?php

namespace Pageflow\Core\modules\images\visuals\import;

use Pageflow\Core\view\TemplateData;
use Pageflow\Core\view\views\Panel;
use Pageflow\Core\view\views\UploadField;

class ImportTab extends Panel {

    public function __construct() {
        parent::__construct('Importeren');
    }

    public function getPanelContentTemplate(): string {
        return "images/templates/import/root.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $data->assign("upload_field", $this->renderUploadField());
    }

    private function renderUploadField(): string {
        $upload_field = new UploadField("import_zip_file", "ZIP bestand", false, "");
        return $upload_field->render();
    }

}
