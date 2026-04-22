<?php

namespace Pageflow\Core\frontend;

use Pageflow\Core\elements\photo_album_element\PhotoAlbumElement;
use Pageflow\Core\modules\articles\model\Article;
use Pageflow\Core\modules\blocks\model\Block;
use Pageflow\Core\modules\pages\model\Page;
use const Pageflow\Core\UPLOAD_DIR;

class PhotoAlbumElementFrontendVisual extends ElementFrontendVisual {

    public function __construct(Page $page, ?Article $article, ?Block $block, PhotoAlbumElement $photoAlbumElement) {
        parent::__construct($page, $article, $block, $photoAlbumElement);
    }

    public function loadElement(array &$data): void {
        $data["title"] = $this->toHtml($this->getElement()->getTitle());
        $data["images"] = $this->getImages();
    }

    private function getImages(): array {
        $images = $this->getElement()->getImages();
        $images_arr = array();
        foreach ($images as $image) {
            if (!$image->isPublished()) continue;
            $imageItem = array();
            $imageItem["id"] = $image->getId();
            $imageItem["title"] = $image->getTitle();
            $imageItem["alt_text"] = $image->getAltText();
            $imageItem["location"] = $image->getLocation();
            $imageItem["url"] = $this->getLinkHelper()->createImageUrl($image);
            $imageItem["url_mobile"] = $this->getLinkHelper()->createMobileImageUrl($image);
            list($width, $height) = getimagesize(UPLOAD_DIR . '/' . $image->getFilename()); // better have a getWidth() and getHeight() on the image?
            $imageItem["width"] = $width;
            $imageItem["height"] = $height;
            $images_arr[] = $imageItem;
        }
        return $images_arr;
    }
}