<?php

namespace Obcato\Core\rest\article;

use Obcato\Core\database\dao\ArticleDao;
use Obcato\Core\database\dao\ArticleDaoMysql;
use Obcato\Core\database\dao\ImageDao;
use Obcato\Core\database\dao\ImageDaoMysql;
use Obcato\Core\rest\Handler;
use Obcato\Core\rest\HttpMethod;

class ArticleHandler extends Handler {

    private ArticleDao $articleDao;
    private ImageDao $imageDao;

    public function __construct() {
        $this->articleDao = ArticleDaoMysql::getInstance();
        $this->imageDao = ImageDaoMysql::getInstance();
        $this->register(HttpMethod::PUT, "/article/update_image", $this->updateImage(...));
        $this->register(HttpMethod::PUT, "/article/update_wallpaper", $this->updateWallpaper(...));
        $this->register(HttpMethod::DELETE, "/article/delete_image", $this->deleteImage(...));
        $this->register(HttpMethod::DELETE, "/article/delete_wallpaper", $this->deleteWallpaper(...));
        $this->register(HttpMethod::GET, "/article/image", $this->getImage(...));
        $this->register(HttpMethod::GET, "/article/wallpaper", $this->getWallpaper(...));
    }

    public function updateImage(array $data): ?array {
        $article = $this->articleDao->getArticle((int)$data['id']);
        $article->setImageId((int)$data['image']);
        $this->articleDao->updateArticle($article);
        return ['element_holder_version' => $article->getVersion()];
    }

    public function updateWallpaper(array $data): ?array {
        $article = $this->articleDao->getArticle((int)$data['id']);
        $article->setWallpaperId((int)$data['image']);
        $this->articleDao->updateArticle($article);
        return ['element_holder_version' => $article->getVersion()];
    }

    public function deleteImage(array $data): ?array {
        $article = $this->articleDao->getArticle((int)$data['id']);
        $article->setImageId(null);
        $this->articleDao->updateArticle($article);
        return ['element_holder_version' => $article->getVersion()];
    }

    public function deleteWallpaper(array $data): ?array {
        $article = $this->articleDao->getArticle((int)$data['id']);
        $article->setWallpaperId(null);
        $this->articleDao->updateArticle($article);
        return ['element_holder_version' => $article->getVersion()];
    }

    public function getImage(?array $data): ?array {
        $article = $this->articleDao->getArticle((int)$_GET['id']);
        $imageId = $article->getImageId();
        
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

    public function getWallpaper(?array $data): ?array {
        $article = $this->articleDao->getArticle((int)$_GET['id']);
        $wallpaperId = $article->getWallpaperId();
        
        if (!$wallpaperId) {
            return null;
        }
        
        $image = $this->imageDao->getImage($wallpaperId);
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
