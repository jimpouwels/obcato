<?php
require_once CMS_ROOT . "/view/views/InformationMessage.php";

class ImageViewer extends Panel {

    private Image $currentImage;

    public function __construct(Image $current) {
        parent::__construct('Afbeelding', 'image_editor');
        $this->currentImage = $current;
    }

    public function getPanelContentTemplate(): string {
        return "modules/images/images/viewer.tpl";
    }

    public function loadPanelContent(Smarty_Internal_Data $data): void {
        $data->assign("title", $this->currentImage->getTitle());
        $data->assign("url", $this->currentImage->getUrl());
    }
}
