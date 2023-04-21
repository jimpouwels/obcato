<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "frontend/element_visual.php";

    class ImageElementFrontendVisual extends ElementFrontendVisual {

        public function __construct(Page $page, ?Article $article, ImageElement $image_element) {
            parent::__construct($page, $article, $image_element);
        }

        public function renderElement(): string {
            $element_holder = $this->getElement()->getElementHolder();
            $this->getTemplateEngine()->assign("title", $this->getElement()->getTitle());
            $this->getTemplateEngine()->assign("alternative_text", $this->toHtml($this->getElement()->getAlternativeText(), $element_holder));
            $this->getTemplateEngine()->assign("align", $this->getElement()->getAlign());
            $this->getTemplateEngine()->assign("width", $this->getElement()->getWidth());
            $this->getTemplateEngine()->assign("height", $this->getElement()->getHeight());
            $this->getTemplateEngine()->assign("image_url", $this->createImageUrl());
            $this->getTemplateEngine()->assign("extension", $this->getExtension());
            return $this->getTemplateEngine()->fetch(FRONTEND_TEMPLATE_DIR . "/" . $this->getElement()->getTemplate()->getFileName());
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