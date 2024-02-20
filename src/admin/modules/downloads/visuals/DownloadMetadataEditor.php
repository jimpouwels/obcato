<?php

namespace Obcato\Core\admin\modules\downloads\visuals;

use Obcato\ComponentApi\TemplateData;
use Obcato\ComponentApi\TemplateEngine;
use Obcato\Core\admin\modules\downloads\model\Download;
use Obcato\Core\admin\view\views\Panel;
use Obcato\Core\admin\view\views\SingleCheckbox;
use Obcato\Core\admin\view\views\TextField;
use Obcato\Core\admin\view\views\UploadField;

class DownloadMetadataEditor extends Panel {
    private Download $download;

    public function __construct(TemplateEngine $templateEngine, Download $download) {
        parent::__construct($templateEngine, 'Algemeen');
        $this->download = $download;
    }

    public function getPanelContentTemplate(): string {
        return "modules/downloads/metadata_editor.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $titleField = new TextField($this->getTemplateEngine(), "download_title", "Titel", $this->download->getTitle(), true, false, null);
        $publishedField = new SingleCheckbox($this->getTemplateEngine(), "download_published", "Gepubliceerd", $this->download->isPublished(), false, null);
        $uploadField = new UploadField($this->getTemplateEngine(), "download_file", "Bestand", false, null);

        $data->assign("download_id", $this->download->getId());
        $data->assign("title_field", $titleField->render());
        $data->assign("published_field", $publishedField->render());
        $data->assign("upload_field", $uploadField->render());
    }
}
