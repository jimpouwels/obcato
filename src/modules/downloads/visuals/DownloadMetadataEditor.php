<?php

namespace Obcato\Core\modules\downloads\visuals;

use Obcato\Core\modules\downloads\model\Download;
use Obcato\Core\view\TemplateData;
use Obcato\Core\view\views\Panel;
use Obcato\Core\view\views\SingleCheckbox;
use Obcato\Core\view\views\TextField;
use Obcato\Core\view\views\UploadField;

class DownloadMetadataEditor extends Panel {
    private Download $download;

    public function __construct(Download $download) {
        parent::__construct('Algemeen');
        $this->download = $download;
    }

    public function getPanelContentTemplate(): string {
        return "modules/downloads/metadata_editor.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $titleField = new TextField("download_title", "Titel", $this->download->getTitle(), true, false, null);
        $publishedField = new SingleCheckbox("download_published", "Gepubliceerd", $this->download->isPublished(), false, null);
        $uploadField = new UploadField("download_file", "Bestand", false, null);

        $data->assign("download_id", $this->download->getId());
        $data->assign("title_field", $titleField->render());
        $data->assign("published_field", $publishedField->render());
        $data->assign("upload_field", $uploadField->render());
    }
}
