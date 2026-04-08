<?php

namespace Obcato\Core\rest\article;

use Obcato\Core\database\dao\ArticleDao;
use Obcato\Core\database\dao\ArticleDaoMysql;
use Obcato\Core\rest\Handler;
use Obcato\Core\rest\HttpMethod;

class ArticleSearchHandler extends Handler {

    private ArticleDao $articleDao;

    public function __construct() {
        $this->articleDao = ArticleDaoMysql::getInstance();
        $this->register(HttpMethod::GET, "/article/search", $this->search(...));
        $this->register(HttpMethod::GET, "/article/get", $this->getArticle(...));
    }

    public function getArticle(): array {
        $id = $_GET["id"] ?? "";
        if (empty($id)) {
            return [];
        }
        
        $article = $this->articleDao->getArticle(intval($id));
        if (!$article) {
            return [];
        }
        
        $description = $article->getDescription();
        return [
            "id" => $article->getId(),
            "title" => $article->getTitle() ?? "",
            "url_title" => $article->getUrlTitle() ?? "",
            "intro" => $description ? substr(strip_tags($description), 0, 100) : ""
        ];
    }

    public function search(): array {
        $keyword = $_GET["keyword"] ?? "";
        if (empty($keyword)) {
            return [];
        }
        
        $allArticles = $this->articleDao->getAllArticles();
        $filteredArticles = array_filter($allArticles, function($article) use ($keyword) {
            $title = $article->getTitle();
            return $title && stripos($title, $keyword) !== false;
        });
        
        $data = [];
        foreach ($filteredArticles as $article) {
            $articleData = array();
            $articleData["id"] = $article->getId();
            $articleData["title"] = $article->getTitle() ?? "";
            $articleData["url_title"] = $article->getUrlTitle() ?? "";
            $description = $article->getDescription();
            if ($description) {
                $articleData["intro"] = substr(strip_tags($description), 0, 100);
            } else {
                $articleData["intro"] = "";
            }
            $data[] = $articleData;
            
            if (count($data) >= 20) break;
        }
        return $data;
    }
}
