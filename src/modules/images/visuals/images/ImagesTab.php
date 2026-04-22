<?php

namespace Pageflow\Core\modules\images\visuals\images;

use Pageflow\Core\modules\images\ImageRequestHandler;
use Pageflow\Core\modules\images\model\Image;
use Pageflow\Core\view\views\Visual;

class ImagesTab extends Visual {

    private ?Image $currentImage;
    private ImageRequestHandler $requestHandler;

    public function __construct(ImageRequestHandler $requestHandler) {
        parent::__construct();
        $this->requestHandler = $requestHandler;
        $this->currentImage = $this->requestHandler->getCurrentImage();
    }

    public function getTemplateFilename(): string {
        return "images/templates/images/root.tpl";
    }

    public function load(): void {
        $this->assign("search", $this->renderImageSearch());
        if ($this->currentImage) {
            $this->assign("editor", $this->renderImageEditor());
        }
    }

    private function renderImageSearch(): string {
        return (new ImageSearch())->render();
    }

    private function renderImageEditor(): string {
        return (new ImageEditor($this->currentImage))->render();
    }

}