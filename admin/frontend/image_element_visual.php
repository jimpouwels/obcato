<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "frontend/element_visual.php";

    class ImageElementFrontendVisual extends ElementFrontendVisual {
        private $_image_element;

        public function __construct($current_page, $image_element) {
            parent::__construct($current_page, $image_element);
            $this->_image_element = $image_element;
        }

        public function renderElement(): string {
            $element_holder = $this->_image_element->getElementHolder();
            $this->getTemplateEngine()->assign("title", $this->_image_element->getTitle());
            $this->getTemplateEngine()->assign("alternative_text", $this->toHtml($this->_image_element->getAlternativeText(), $element_holder));
            $this->getTemplateEngine()->assign("align", $this->_image_element->getAlign());
            $this->getTemplateEngine()->assign("width", $this->_image_element->getWidth());
            $this->getTemplateEngine()->assign("height", $this->_image_element->getHeight());
            $this->getTemplateEngine()->assign("image_url", $this->createImageUrl());
            $this->getTemplateEngine()->assign("extension", $this->getExtension());
            return $this->getTemplateEngine()->fetch(FRONTEND_TEMPLATE_DIR . "/" . $this->_image_element->getTemplate()->getFileName());
        }

        private function createImageUrl() {
            $image_url = "";
            if (!is_null($this->_image_element->getImage())) {
                $image_url = $this->getImageUrl($this->_image_element->getImage());
            }
            return $image_url;
        }

        private function getExtension() {
            $extension = "";
                if (!is_null($this->_image_element->getImage()))
                $extension = $this->_image_element->getImage()->getExtension();
            return $extension;
        }
    }