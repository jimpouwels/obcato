<?php

namespace Pageflow\Core\modules\blocks\service;

use Pageflow\Core\modules\pages\model\Page;

interface BlockService {

    public function deleteBlockFromPage(int $blockId, Page $page): void;

    public function getBlocksByPage(Page $page): array;
}