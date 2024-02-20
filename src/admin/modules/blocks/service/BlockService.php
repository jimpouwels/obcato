<?php

namespace Obcato\Core;

interface BlockService {

    public function deleteBlockFromPage(int $blockId, Page $page): void;

    public function getBlocksByPage(Page $page): array;
}