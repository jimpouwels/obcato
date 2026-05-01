<?php

namespace Pageflow\Core\rest\link;

use Pageflow\Core\modules\links\database\dao\ReusableLinkDaoMysql;
use Pageflow\Core\rest\Handler;
use Pageflow\Core\rest\HttpMethod;

class LinkHandler extends Handler {

    public function __construct() {
        $this->register(HttpMethod::GET, "/link/tree", $this->getTree(...));
    }

    public function getTree(): ?array {
        $tree = ReusableLinkDaoMysql::getInstance()->getFolderTree();
        return [
            'folders' => $this->serializeFolders($tree['folders']),
            'links'   => $this->serializeLinks($tree['links']),
        ];
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
            'title' => $l->getTitle(),
            'url'   => $l->getUrl(),
        ], $links);
    }
}
