<?php

namespace Obcato\Core;

use Obcato\ComponentApi\TemplateData;
use Obcato\ComponentApi\TemplateEngine;

class ImageViewer extends Panel {

    private Image $currentImage;

    public function __construct(TemplateEngine $templateEngine, Image $current) {
        parent::__construct($templateEngine, 'Afbeelding', 'image_editor');
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
