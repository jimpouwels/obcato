<?php

namespace Obcato\Core\admin\modules\images\visuals\images;

use Obcato\ComponentApi\TemplateData;
use Obcato\Core\admin\modules\images\model\Image;
use Obcato\Core\admin\view\views\Panel;

class ImageViewer extends Panel {

    private Image $currentImage;

    public function __construct(Image $current) {
        parent::__construct('Afbeelding', 'image_editor');
        $this->currentImage = $current;
    }

    public function getPanelContentTemplate(): string {
        return "modules/images/images/viewer.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $data->assign("title", $this->currentImage->getTitle());
        $data->assign("url", $this->currentImage->getUrl());
    }
}