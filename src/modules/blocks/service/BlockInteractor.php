<?php

namespace Obcato\Core\modules\blocks\service;

use Obcato\Core\database\dao\BlockDao;
use Obcato\Core\database\dao\BlockDaoMysql;
use Obcato\Core\modules\pages\model\Page;

class BlockInteractor implements BlockService {

    private static ?BlockInteractor $instance = null;

    private BlockDao $blockDao;

    private function __construct() {
        $this->blockDao = BlockDaoMysql::getInstance();
    }

    public static function getInstance(): BlockInteractor {
        if (!self::$instance) {
            self::$instance = new BlockInteractor();
        }
        return self::$instance;
    }

    public function deleteBlockFromPage(int $blockId, Page $page): void {
        $this->blockDao->deleteBlockFromPage($blockId, $page);
    }

    public function getBlocksByPage(Page $page): array {
        return $this->blockDao->getBlocksByPage($page);
    }
}