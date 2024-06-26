<?php

namespace Obcato\Core\frontend;

use Obcato\Core\elements\image_element\ImageElement;
use Obcato\Core\modules\articles\model\Article;
use Obcato\Core\modules\pages\model\Page;

class ImageElementFrontendVisual extends ElementFrontendVisual {

    public function __construct(Page $page, ?Article $article, ImageElement $imageElement) {
        parent::__construct($page, $article, $imageElement);
    }

    public function loadElement(): void {
        $this->assign("title", $this->getElement()->getTitle());
        $this->assign("img_title", $this->getElement()->getImage()?->getTitle());
        $this->assign("img_alt_text", $this->getElement()->getImage()?->getAltText());
        $this->assign("align", $this->getElement()->getAlign());
        $this->assign("width", $this->getElement()->getWidth());
        $this->assign("height", $this->getElement()->getHeight());
        $this->assign("image_url", $this->createImageUrl());
        $this->assign("extension", $this->getExtension());
    }

    private function createImageUrl(): string {
        return $this->getImageUrl($this->getElement()->getImage());
    }

    private function getExtension(): string {
        $image = $this->getElement()->getImage();
        return $image ? $image?->getExtension() : "";
    }
}