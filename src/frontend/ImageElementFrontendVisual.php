<?php

namespace Obcato\Core\frontend;

use Obcato\Core\elements\image_element\ImageElement;
use Obcato\Core\modules\articles\model\Article;
use Obcato\Core\modules\blocks\model\Block;
use Obcato\Core\modules\pages\model\Page;

class ImageElementFrontendVisual extends ElementFrontendVisual {

    public function __construct(Page $page, ?Article $article, ?Block $block, ImageElement $imageElement) {
        parent::__construct($page, $article, $block, $imageElement);
    }

    public function loadElement(array &$data): void {
        $data["title"] = $this->getElement()->getTitle();
        $data["img_title"] = $this->getElement()->getImage()?->getTitle();
        $data["img_alt_text"] = $this->getElement()->getImage()?->getAltText();
        $data["align"] = $this->getElement()->getAlign();
        $data["width"] = $this->getElement()->getWidth();
        $data["height"] = $this->getElement()->getHeight();
        $data["image_url"] = $this->createImageUrl();
        $data["extension"] = $this->getExtension();
    }

    private function createImageUrl(): string {
        return $this->getImageUrl($this->getElement()->getImage());
    }

    private function getExtension(): string {
        $image = $this->getElement()->getImage();
        return $image ? $image->getExtension() : "";
    }
}