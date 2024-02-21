<?php

namespace Obcato\Core\admin\view\views;

use Obcato\ComponentApi\TemplateData;
use Obcato\Core\admin\database\dao\BlockDao;
use Obcato\Core\admin\database\dao\BlockDaoMysql;
use Obcato\Core\admin\modules\blocks\model\Block;
use Obcato\Core\admin\utilities\Arrays;

class BlockSelector extends Panel {

    private array $selectedBlocks;
    private BlockDao $blockDao;
    private int $contextId;

    public function __construct(array $selectedBlocks, int $contextId) {
        parent::__construct($this->getTextResource('block_selection_title'), 'page_blocks');
        $this->blockDao = BlockDaoMysql::getInstance();
        $this->contextId = $contextId;
        $this->selectedBlocks = $selectedBlocks;
    }

    public function getPanelContentTemplate(): string {
        return "system/block_selector.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $data->assign("blocks_to_select", $this->getBlocksToSelect());
        $data->assign("selected_blocks", $this->getSelectedBlocksHtml());
        $data->assign("context_id", $this->contextId);
    }

    private function getBlocksToSelect(): array {
        $blocks_to_select = array();
        foreach ($this->blockDao->getAllBlocks() as $block) {
            if (!Arrays::firstMatch($this->selectedBlocks, function (Block $b) use ($block) {
                return $b->getId() == $block->getId();
            })) {
                $block_to_select["id"] = $block->getId();
                $block_to_select["title"] = $block->getTitle();
                $blocks_to_select[] = $block_to_select;
            }
        }
        return $blocks_to_select;
    }

    private function getSelectedBlocksHtml(): array {
        $selected_blocks = array();
        if (count($this->selectedBlocks) > 0) {
            foreach ($this->selectedBlocks as $selected_block) {
                $selected_block_item = array();
                $selected_block_item["title"] = $selected_block->getTitle();
                $selected_block_item["position_name"] = $selected_block->getPositionName();
                $selected_block_item["published"] = $selected_block->isPublished();

                $delete_field = new SingleCheckbox("block_" . $this->contextId . "_" . $selected_block->getId() . "_delete", "", false, false, "");
                $selected_block_item["delete_field"] = $delete_field->render();
                $selected_blocks[] = $selected_block_item;
            }
        }
        return $selected_blocks;
    }

}