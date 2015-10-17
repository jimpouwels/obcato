<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "frontend/frontend_visual.php";

    class ImageElementFrontendVisual extends FrontendVisual {

        private $_template_engine;
        private $_image_element;

        public function __construct($current_page, $image_element) {
            parent::__construct($current_page);
            $this->_template_engine = TemplateEngine::getInstance();
            $this->_image_element = $image_element;
        }

        public function render() {
            $element_holder = $this->_image_element->getElementHolder();
            $this->_template_engine->assign("title", $this->_image_element->getTitle());
            $this->_template_engine->assign("alternative_text", $this->toHtml($this->_image_element->getAlternativeText(), $element_holder));
            $this->_template_engine->assign("align", $this->_image_element->getAlign());
            $this->_template_engine->assign("width", $this->_image_element->getWidth());
            $this->_template_engine->assign("height", $this->_image_element->getHeight());
            $this->_template_engine->assign("image_url", $this->createImageUrl());
            $this->_template_engine->assign("extension", $this->getExtension());
            return $this->_template_engine->fetch(FRONTEND_TEMPLATE_DIR . "/" . $this->_image_element->getTemplate()->getFileName());
        }

        private function createImageUrl() {
            $image_url = "";
            if (!is_null($this->_image_element->getImage()))
                $image_url = $this->getImageUrl($this->_image_element->getImage());
            return $image_url;
        }

        private function getExtension() {
            $extension = "";
                if (!is_null($this->_image_element->getImage()))
                $extension = $this->_image_element->getImage()->getExtension();
            return $extension;
        }
    }