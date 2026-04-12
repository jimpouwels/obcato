<?php

namespace Obcato\Core\rest\image;

use Obcato\Core\database\dao\ImageDao;
use Obcato\Core\database\dao\ImageDaoMysql;
use Obcato\Core\rest\Handler;
use Obcato\Core\rest\HttpMethod;

class ImageHandler extends Handler {

    private ImageDao $imageDao;

    public function __construct() {
        $this->imageDao = ImageDaoMysql::getInstance();
        $this->register(HttpMethod::GET, "/image/search", $this->search(...));
        $this->register(HttpMethod::DELETE, "/image/delete", $this->delete(...));
    }

    public function search(): array {
        $images = $this->imageDao->searchImages($_GET["keyword"], null, 50);
        $data = [];
        foreach ($images as $image) {
            $imageData = array();
            $imageData["id"] = $image->getId();
            $imageData["title"] = $image->getTitle();
            $imageData["alternative_text"] = $image->getAltText();
            $imageData["url"] = $image->getThumbUrl();
            $data[] = $imageData;
        }
        return $data;
    }

    public function delete(): ?array {
        $this->imageDao->deleteImage($this->imageDao->getImage((int)$_GET["id"]));
        return null;
    }
}