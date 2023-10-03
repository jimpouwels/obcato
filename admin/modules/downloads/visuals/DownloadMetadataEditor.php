<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/view/views/InformationMessage.php";

class DownloadMetadataEditor extends Panel {
    private Download $_download;

    public function __construct(Download $download) {
        parent::__construct('Algemeen');
        $this->_download = $download;
    }

    public function getPanelContentTemplate(): string {
        return "modules/downloads/metadata_editor.tpl";
    }

    public function loadPanelContent(Smarty_Internal_Data $data): void {
        $title_field = new TextField("download_title", "Titel", $this->_download->getTitle(), true, false, null);
        $published_field = new SingleCheckbox("download_published", "Gepubliceerd", $this->_download->isPublished(), false, null);
        $upload_field = new UploadField("download_file", "Bestand", false, null);

        $data->assign("download_id", $this->_download->getId());
        $data->assign("title_field", $title_field->render());
        $data->assign("published_field", $published_field->render());
        $data->assign("upload_field", $upload_field->render());
    }
}
