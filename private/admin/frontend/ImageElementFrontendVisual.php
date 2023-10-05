<?php
require_once CMS_ROOT . "/frontend/ElementFrontendVisual.php";

class ImageElementFrontendVisual extends ElementFrontendVisual {

    public function __construct(Page $page, ?Article $article, ImageElement $image_element) {
        parent::__construct($page, $article, $image_element);
    }

    public function loadElement(): void {
        $this->assign("title", $this->getElement()->getTitle());
        $this->assign("img_title", $this->getElement()->getImage()->getTitle());
        $this->assign("img_alt_text", $this->getElement()->getImage()->getAltText());
        $this->assign("align", $this->getElement()->getAlign());
        $this->assign("width", $this->getElement()->getWidth());
        $this->assign("height", $this->getElement()->getHeight());
        $this->assign("image_url", $this->createImageUrl());
        $this->assign("extension", $this->getExtension());
    }

    private function createImageUrl(): string {
        $image_url = "";
        if (!is_null($this->getElement()->getImage())) {
            $image_url = $this->getImageUrl($this->getElement()->getImage());
        }
        return $image_url;
    }

    private function getExtension(): string {
        $extension = "";
        if (!is_null($this->getElement()->getImage())) {
            $extension = $this->getElement()->getImage()->getExtension();
        }
        return $extension;
    }
}