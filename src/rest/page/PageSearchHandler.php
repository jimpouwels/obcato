<?php

namespace Pageflow\Core\rest\page;

use Pageflow\Core\database\dao\PageDao;
use Pageflow\Core\database\dao\PageDaoMysql;
use Pageflow\Core\rest\Handler;
use Pageflow\Core\rest\HttpMethod;

class PageSearchHandler extends Handler {

    private PageDao $pageDao;

    public function __construct() {
        $this->pageDao = PageDaoMysql::getInstance();
        $this->register(HttpMethod::GET, "/page/search", $this->search(...));
        $this->register(HttpMethod::GET, "/page/get", $this->getPage(...));
    }

    public function getPage(): array {
        $id = $_GET["id"] ?? "";
        if (empty($id)) {
            return [];
        }
        
        $page = $this->pageDao->getPage(intval($id));
        if (!$page) {
            return [];
        }
        
        return [
            "id" => $page->getId(),
            "title" => $page->getTitle(),
            "url_title" => $page->getUrlTitle(),
            "path" => $this->getPagePath($page)
        ];
    }

    public function search(): array {
        $keyword = $_GET["keyword"] ?? "";
        if (empty($keyword)) {
            return [];
        }
        
        $allPages = $this->pageDao->getAllPages();
        $filteredPages = array_filter($allPages, function($page) use ($keyword) {
            $title = $page->getTitle();
            return $title && stripos($title, $keyword) !== false;
        });
        
        $data = [];
        foreach ($filteredPages as $page) {
            $pageData = array();
            $pageData["id"] = $page->getId();
            $pageData["title"] = $page->getTitle();
            $pageData["url_title"] = $page->getUrlTitle();
            $pageData["path"] = $this->getPagePath($page);
            $data[] = $pageData;
            
            if (count($data) >= 20) break;
        }
        return $data;
    }
    
    private function getPagePath($page): string {
        $path = [];
        $currentPage = $page;
        $maxDepth = 10;
        $depth = 0;
        
        while ($currentPage && $depth < $maxDepth) {
            array_unshift($path, $currentPage->getTitle());
            $parentId = $currentPage->getParentId();
            if ($parentId) {
                $currentPage = $this->pageDao->getPage($parentId);
            } else {
                break;
            }
            $depth++;
        }
        
        return implode(' > ', $path);
    }
}
