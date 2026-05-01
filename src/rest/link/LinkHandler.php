<?php

namespace Pageflow\Core\rest\link;

use Pageflow\Core\modules\links\database\dao\ReusableLinkDaoMysql;
use Pageflow\Core\rest\Handler;
use Pageflow\Core\rest\HttpMethod;

class LinkHandler extends Handler {

    public function __construct() {
        $this->register(HttpMethod::GET, "/link/tree", $this->getTree(...));
        $this->register(HttpMethod::GET, "/link/get", $this->getLink(...));
        $this->register(HttpMethod::GET, "/link/search", $this->searchLinks(...));
    }

    public function getTree(): ?array {
        $tree = ReusableLinkDaoMysql::getInstance()->getFolderTree();
        return [
            'folders' => $this->serializeFolders($tree['folders']),
            'links'   => $this->serializeLinks($tree['links']),
        ];
    }

    public function getLink(): ?array {
        $id = $_GET['id'] ?? '';
        if (empty($id)) {
            return null;
        }
        $link = ReusableLinkDaoMysql::getInstance()->getLink(intval($id));
        if (!$link) {
            return null;
        }
        return [
            'id'    => $link->getId(),
            'name' => $link->getName(),
            'title' => $link->getTitle(),
            'url'   => $link->getUrl(),
        ];
    }

    public function searchLinks(): ?array {
        $keyword = $_GET['keyword'] ?? '';
        $links = ReusableLinkDaoMysql::getInstance()->searchLinks($keyword);
        return $this->serializeLinks($links);
    }

    private function serializeFolders(array $folders): array {
        return array_map(function ($folder) {
            return [
                'id'         => $folder->getId(),
                'name'       => $folder->getName(),
                'links'      => $this->serializeLinks($folder->getLinks()),
                'subFolders' => $this->serializeFolders($folder->getSubFolders()),
            ];
        }, $folders);
    }

    private function serializeLinks(array $links): array {
        return array_map(fn($l) => [
            'id'    => $l->getId(),
            'name' => $l->getName(),
            'title' => $l->getTitle(),
            'url'   => $l->getUrl(),
        ], $links);
    }
}
