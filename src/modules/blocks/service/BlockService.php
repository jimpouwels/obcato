<?php

namespace Obcato\Core\modules\blocks\service;

use Obcato\Core\modules\pages\model\Page;

interface BlockService {

    public function deleteBlockFromPage(int $blockId, Page $page): void;

    public function getBlocksByPage(Page $page): array;
}