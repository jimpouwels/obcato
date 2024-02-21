<?php

namespace Obcato\Core\admin\modules\blocks\visuals\blocks;

use Obcato\ComponentApi\TemplateData;
use Obcato\Core\admin\database\dao\BlockDao;
use Obcato\Core\admin\database\dao\BlockDaoMysql;
use Obcato\Core\admin\modules\blocks\model\Block;
use Obcato\Core\admin\modules\blocks\model\BlockPosition;
use Obcato\Core\admin\view\views\InformationMessage;
use Obcato\Core\admin\view\views\Panel;

class BlocksList extends Panel {

    private ?Block $currentBlock;
    private BlockDao $blockDao;

    public function __construct(?Block $current) {
        parent::__construct('Blokken', 'block_list');
        $this->currentBlock = $current;
        $this->blockDao = BlockDaoMysql::getInstance();
    }

    public
    function getPanelContentTemplate(): string {
        return "modules/blocks/blocks/list.tpl";
    }

    public
    function loadPanelContent(TemplateData $data): void {
        $data->assign("block_lists", $this->getBlockLists());
        $data->assign("no_results_message", $this->renderNoResultsMessage());
        $currentBlockValue = null;
        if (!is_null($this->currentBlock)) {
            $currentBlockValue = $this->toArray($this->currentBlock);
        }
        $data->assign("current_block", $currentBlockValue);
    }

    private
    function renderNoResultsMessage(): string {
        $noResultMessage = new InformationMessage($this->getTextResource("blocks_no_blocks_found"));
        return $noResultMessage->render();
    }

    private
    function getBlockLists(): array {
        $blockLists = array();
        // blocks with position
        foreach ($this->blockDao->getBlockPositions() as $position) {
            $blockList = array();
            $blockList["position"] = $position->getName();
            $blockList["blocks"] = $this->getBlocks($position);
            $blockLists[] = $blockList;
        }
        // blocks without position
        $blockList = array();
        $blockList["position"] = null;
        $blockList["blocks"] = $this->getBlocks(null);
        $blockLists[] = $blockList;
        return $blockLists;
    }

    private
    function getBlocks(?BlockPosition $position): array {
        $blocks = array();
        if (!is_null($position)) {
            foreach ($this->blockDao->getBlocksByPosition($position) as $block) {
                $blocks[] = $this->toArray($block);
            }
        } else {
            foreach ($this->blockDao->getBlocksWithoutPosition() as $block) {
                $blocks[] = $this->toArray($block);
            }
        }
        return $blocks;
    }

    private
    function toArray(Block $block): array {
        $blockValue = array();
        $blockValue["id"] = $block->getId();
        $blockValue["title"] = $block->getTitle();
        $blockValue["published"] = $block->isPublished();
        return $blockValue;
    }
}
