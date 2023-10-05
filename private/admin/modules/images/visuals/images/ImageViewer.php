<?php
require_once CMS_ROOT . "/view/views/InformationMessage.php";

class ImageViewer extends Panel {

    private Image $_current_image;

    public function __construct(Image $current_image) {
        parent::__construct('Afbeelding', 'image_editor');
        $this->_current_image = $current_image;
    }

    public function getPanelContentTemplate(): string {
        return "modules/images/images/viewer.tpl";
    }

    public function loadPanelContent(Smarty_Internal_Data $data): void {
        $data->assign("title", $this->_current_image->getTitle());
        $data->assign("url", $this->_current_image->getUrl());
    }
}
