<?php

    defined('_ACCESS') or die;

    require_once CMS_ROOT . "frontend/element_visual.php";

    class PhotoAlbumElementFrontendVisual extends ElementFrontendVisual {

        private $_template_engine;
        private $_photo_album_element;

        public function __construct($current_page, $photo_album_element) {
            parent::__construct($current_page, $photo_album_element);
            $this->_template_engine = TemplateEngine::getInstance();
            $this->_photo_album_element = $photo_album_element;
        }

        public function renderElement(): string {
            $element_holder = $this->_photo_album_element->getElementHolder();
            $this->_template_engine->assign("title", $this->toHtml($this->_photo_album_element->getTitle(), $element_holder));
            $this->_template_engine->assign("images", $this->getImages());
            return $this->_template_engine->fetch(FRONTEND_TEMPLATE_DIR . "/" . $this->_photo_album_element->getTemplate()->getFileName());
        }

        private function getImages() {
            $images = $this->_photo_album_element->getImages();
            $images_arr = array();
            foreach ($images as $image) {
                if (!$image->isPublished()) continue;
                $image_item = array();
                $image_item["id"] = $image->getId();
                $image_item["title"] = $image->getTitle();
                $image_item["url"] = $this->getImageUrl($image);
                $images_arr[] = $image_item;
            }
            return $images_arr;
        }
    }

?>
