<?php

namespace Obcato\Core\admin\modules\blocks\service;

use Obcato\Core\admin\modules\pages\model\Page;

interface BlockService {

    public function deleteBlockFromPage(int $blockId, Page $page): void;

    public function getBlocksByPage(Page $page): array;
}