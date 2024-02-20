<?php

namespace Obcato\Core\admin\modules\blocks\service;

use Obcato\Core\admin\database\dao\BlockDao;
use Obcato\Core\admin\database\dao\BlockDaoMysql;
use Obcato\Core\admin\modules\pages\model\Page;

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