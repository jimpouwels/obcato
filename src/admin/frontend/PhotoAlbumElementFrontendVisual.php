<?php

namespace Obcato\Core\admin\frontend;

use Obcato\Core\admin\elements\photo_album_element\PhotoAlbumElement;
use Obcato\Core\admin\modules\articles\model\Article;
use Obcato\Core\admin\modules\pages\model\Page;

class PhotoAlbumElementFrontendVisual extends ElementFrontendVisual {

    public function __construct(Page $page, ?Article $article, PhotoAlbumElement $photoAlbumElement) {
        parent::__construct($page, $article, $photoAlbumElement);
    }

    public function loadElement(): void {
        $this->assign("title", $this->toHtml($this->getElement()->getTitle(), $this->getElementHolder()));
        $this->assign("images", $this->getImages());
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
            $imageItem["url"] = $this->getImageUrl($image);
            $images_arr[] = $imageItem;
        }
        return $images_arr;
    }
}