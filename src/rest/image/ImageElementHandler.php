<?php

namespace Pageflow\Core\rest\image;

use Pageflow\Core\database\dao\ElementDao;
use Pageflow\Core\database\dao\ElementDaoMysql;
use Pageflow\Core\database\dao\ImageDao;
use Pageflow\Core\database\dao\ImageDaoMysql;
use Pageflow\Core\rest\Handler;
use Pageflow\Core\rest\HttpMethod;

class ImageElementHandler extends Handler {

    private ElementDao $elementDao;
    private ImageDao $imageDao;

    public function __construct() {
        parent::__construct();
        $this->elementDao = ElementDaoMysql::getInstance();
        $this->imageDao = ImageDaoMysql::getInstance();
        $this->register(HttpMethod::PUT, "/image_element/update_image", $this->updateElement(...));
        $this->register(HttpMethod::DELETE, "/image_element/delete_image", $this->deleteImage(...));
        $this->register(HttpMethod::GET, "/image_element/image", $this->getImage(...));
    }

    public function updateElement(array $data): ?array {
        $element = $this->elementDao->getElement($data['id']);
        $element->setImageId($data['image']);
        $element->updateMetaData();
        return ['element_holder_version' => $this->bumpElementHolderVersion($element->getElementHolderId())];
    }

    public function deleteImage(array $data): ?array {
        $element = $this->elementDao->getElement($data['id']);
        $element->setImageId(null);
        $element->updateMetaData();
        return ['element_holder_version' => $this->bumpElementHolderVersion($element->getElementHolderId())];
    }

    public function getImage(?array $data): ?array {
        $element = $this->elementDao->getElement($_GET['id']);
        $imageId = $element->getImageId();
        
        if (!$imageId) {
            return null;
        }
        
        $image = $this->imageDao->getImage($imageId);
        if (!$image) {
            return null;
        }
        
        return [
            "id" => $image->getId(),
            "title" => $image->getTitle(),
            "alternative_text" => $image->getAltText(),
            "url" => $image->getThumbUrl()
        ];
    }
}
