<?php

    defined('_ACCESS') or die;

    require_once CMS_ROOT . "frontend/element_visual.php";

    class PhotoAlbumElementFrontendVisual extends ElementFrontendVisual {

        public function __construct(Page $page, ?Article $article, PhotoAlbumElement $photo_album_element) {
            parent::__construct($page, $article, $photo_album_element);
        }

        public function renderElement(): string {
            $element_holder = $this->getElement()->getElementHolder();
            $this->getTemplateEngine()->assign("title", $this->toHtml($this->getElement()->getTitle(), $element_holder));
            $this->getTemplateEngine()->assign("images", $this->getImages());
            return $this->getTemplateEngine()->fetch(FRONTEND_TEMPLATE_DIR . "/" . $this->getElement()->getTemplate()->getFileName());
        }

        private function getImages(): array {
            $images = $this->getElement()->getImages();
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
