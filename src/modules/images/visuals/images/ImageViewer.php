<?php

namespace Obcato\Core\modules\images\visuals\images;

use Obcato\Core\modules\images\model\Image;
use Obcato\Core\view\TemplateData;
use Obcato\Core\view\views\Panel;

class ImageViewer extends Panel {

    private Image $currentImage;

    public function __construct(Image $current) {
        parent::__construct('Afbeelding', 'image_editor');
        $this->currentImage = $current;
    }

    public function getPanelContentTemplate(): string {
        return "images/templates/images/viewer.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $data->assign("title", $this->currentImage->getTitle());
        $data->assign("url", $this->currentImage->getUrl());
    }
}