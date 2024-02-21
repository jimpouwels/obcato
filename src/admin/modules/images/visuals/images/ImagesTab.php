<?php

namespace Obcato\Core\admin\modules\images\visuals\images;

use Obcato\ComponentApi\TemplateEngine;
use Obcato\ComponentApi\Visual;
use Obcato\Core\admin\modules\images\ImageRequestHandler;
use Obcato\Core\admin\modules\images\model\Image;

class ImagesTab extends Visual {

    private ?Image $currentImage;
    private ImageRequestHandler $requestHandler;

    public function __construct(TemplateEngine $templateEngine, ImageRequestHandler $requestHandler) {
        parent::__construct($templateEngine);
        $this->requestHandler = $requestHandler;
        $this->currentImage = $this->requestHandler->getCurrentImage();
    }

    public function getTemplateFilename(): string {
        return "modules/images/images/root.tpl";
    }

    public function load(): void {
        $this->assign("search", $this->renderImageSearch());
        if ($this->currentImage) {
            $this->assign("editor", $this->renderImageEditor());
        } else {
            $this->assign("list", $this->renderImageList());
        }
    }

    private function renderImageSearch(): string {
        return (new ImageSearch($this->getTemplateEngine(), $this->requestHandler))->render();
    }

    private function renderImageList(): string {
        return (new ImageList($this->getTemplateEngine(), $this->requestHandler))->render();
    }

    private function renderImageEditor(): string {
        return (new ImageEditor($this->getTemplateEngine(), $this->currentImage))->render();
    }

}

?>