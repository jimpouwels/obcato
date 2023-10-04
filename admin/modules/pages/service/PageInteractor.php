<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . '/modules/pages/service/PageService.php';
require_once CMS_ROOT . '/database/dao/PageDaoMysql.php';
require_once CMS_ROOT . '/database/dao/BlockDaoMysql.php';

class PageInteractor implements PageService {
    private static ?PageInteractor $instance = null;

    private PageDao $pageDao;
    private BlockDao $blockDao;

    private function __construct() {
        $this->pageDao = PageDaoMysql::getInstance();
        $this->blockDao = BlockDaoMysql::getInstance();
    }

    public static function getInstance(): PageInteractor {
        if (!self::$instance) {
            self::$instance = new PageInteractor();
        }
        return self::$instance;
    }

    function addSelectedBlocks(Page $page, array $selected_blocks): void {
        if (count($selected_blocks) == 0) return;
        $current_page_blocks = $this->blockDao->getBlocksByPage($page);
        foreach ($selected_blocks as $selected_block_id) {
            if (!$this->blockAlreadyExists($selected_block_id, $current_page_blocks)) {
                $this->blockDao->addBlockToPage($selected_block_id, $page);
            }
        }
    }

    private function blockAlreadyExists(int $selected_block_id, array $current_page_blocks): bool {
        foreach ($current_page_blocks as $current_page_block) {
            if ($current_page_block->getId() == $selected_block_id) {
                return true;
            }
        }
        return false;
    }
}