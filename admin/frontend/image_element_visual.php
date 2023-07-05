<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "frontend/element_visual.php";

    class ImageElementFrontendVisual extends ElementFrontendVisual {

        public function __construct(Page $page, ?Article $article, ImageElement $image_element) {
            parent::__construct($page, $article, $image_element);
        }

        public function loadElement(Smarty_Internal_Data $data): void {
            $element_holder = $this->getElement()->getElementHolder();
            $data->assign("title", $this->getElement()->getTitle());
            $data->assign("alt_text", $this->toHtml($this->getElement()->getImage()->getAltText(), $element_holder));
            $data->assign("align", $this->getElement()->getAlign());
            $data->assign("width", $this->getElement()->getWidth());
            $data->assign("height", $this->getElement()->getHeight());
            $data->assign("image_url", $this->createImageUrl());
            $data->assign("extension", $this->getExtension());
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