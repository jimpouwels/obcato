<?php

namespace Obcato\Core\rest\image;

use Obcato\Core\database\dao\ImageDao;
use Obcato\Core\database\dao\ImageDaoMysql;
use Obcato\Core\frontend\helper\LinkHelper;
use Obcato\Core\rest\Handler;
use Obcato\Core\rest\HttpMethod;

class ImageHandler extends Handler {

    private ImageDao $imageDao;

    public function __construct() {
        $this->imageDao = ImageDaoMysql::getInstance();
        $this->register(HttpMethod::GET, "/image/search", $this->search(...));
    }

    public function search(): array {
        $images = $this->imageDao->searchImages($_GET["keyword"], null, null, 50);
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
}