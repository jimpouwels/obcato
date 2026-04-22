<?php

namespace Pageflow\Core\rest\image;

use Pageflow\Core\database\dao\ElementDao;
use Pageflow\Core\database\dao\ElementDaoMysql;
use Pageflow\Core\database\dao\ImageDao;
use Pageflow\Core\database\dao\ImageDaoMysql;
use Pageflow\Core\rest\Handler;
use Pageflow\Core\rest\HttpMethod;

class PhotoAlbumElementHandler extends Handler {

    private ElementDao $elementDao;
    private ImageDao $imageDao;

    public function __construct() {
        parent::__construct();
        $this->elementDao = ElementDaoMysql::getInstance();
        $this->imageDao = ImageDaoMysql::getInstance();
        $this->register(HttpMethod::PUT, "/photo_album_element/add_image", $this->updateElement(...));
        $this->register(HttpMethod::GET, "/photo_album_element/images", $this->getImages(...));
        $this->register(HttpMethod::DELETE, "/photo_album_element/delete_image", $this->deleteImage(...));
        $this->register(HttpMethod::POST, "/photo_album_element/reorder_images", $this->reorderImages(...));
    }

    public function updateElement(array $data): ?array {
        $element = $this->elementDao->getElement($data['id']);
        $element->addImage($data['image']);
        $element->updateMetaData();
        return ['element_holder_version' => $this->bumpElementHolderVersion($element->getElementHolderId())];
    }

    public function deleteImage(array $data): ?array {
        $element = $this->elementDao->getElement($data['id']);
        $element->deleteImage($data['image']);
        $element->updateMetaData();
        return ['element_holder_version' => $this->bumpElementHolderVersion($element->getElementHolderId())];
    }

    public function getImages(?array $data): ?array {
        $element = $this->elementDao->getElement($_GET['id']);
        $data = [];
        foreach ($element->getImageIds() as $imageId) {
            $image = $this->imageDao->getImage($imageId);
            $imageData = array();
            $imageData["id"] = $image->getId();
            $imageData["title"] = $image->getTitle();
            $imageData["alternative_text"] = $image->getAltText();
            $imageData["url"] = $image->getThumbUrl();
            $data[] = $imageData;
        }
        return $data;
    }

    public function reorderImages(array $data): ?array {
        $element = $this->elementDao->getElement($data['id']);
        $imageIds = $data['imageIds'];
        
        // Update the element with the new order
        $element->setImageIds($imageIds);
        $element->updateMetaData();
        
        return ['element_holder_version' => $this->bumpElementHolderVersion($element->getElementHolderId())];
    }
}