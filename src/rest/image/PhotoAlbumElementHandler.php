<?php

namespace Obcato\Core\rest\image;

use Obcato\Core\database\dao\ElementDao;
use Obcato\Core\database\dao\ElementDaoMysql;
use Obcato\Core\database\dao\ImageDao;
use Obcato\Core\database\dao\ImageDaoMysql;
use Obcato\Core\rest\Handler;
use Obcato\Core\rest\HttpMethod;

class PhotoAlbumElementHandler extends Handler {

    private ElementDao $elementDao;
    private ImageDao $imageDao;

    public function __construct() {
        $this->elementDao = ElementDaoMysql::getInstance();
        $this->imageDao = ImageDaoMysql::getInstance();
        $this->register(HttpMethod::PUT, "/photo_album_element/add_image", $this->updateElement(...));
        $this->register(HttpMethod::GET, "/photo_album_element/images", $this->getImages(...));
        $this->register(HttpMethod::DELETE, "/photo_album_element/delete_image", $this->deleteImage(...));

    }

    public function updateElement(array $data): ?array {
        $element = $this->elementDao->getElement($data['id']);
        $element->addImage($data['image']);
        $element->updateMetaData();
        return null;
    }

    public function deleteImage(array $data): ?array {
        $element = $this->elementDao->getElement($data['id']);
        $element->deleteImage($data['image']);
        $element->updateMetaData();
        return null;
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
}