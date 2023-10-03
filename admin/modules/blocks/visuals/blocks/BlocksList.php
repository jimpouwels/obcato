<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/view/views/InformationMessage.php";

class BlocksList extends Panel {

    private ?Block $_current_block;
    private BlockDao $_block_dao;

    public function __construct(?Block $current_block) {
        parent::__construct('Blokken', 'block_list');
        $this->_current_block = $current_block;
        $this->_block_dao = BlockDaoMysql::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return "modules/blocks/blocks/list.tpl";
    }

    public function loadPanelContent(Smarty_Internal_Data $data): void {
        $data->assign("block_lists", $this->getBlockLists());
        $data->assign("no_results_message", $this->renderNoResultsMessage());
        $current_block_value = null;
        if (!is_null($this->_current_block)) {
            $current_block_value = $this->toArray($this->_current_block);
        }
        $data->assign("current_block", $current_block_value);
    }

    private function renderNoResultsMessage(): string {
        $no_result_message = new InformationMessage($this->getTextResource("blocks_no_blocks_found"));
        return $no_result_message->render();
    }

    private function getBlockLists(): array {
        $block_lists = array();
        // blocks with position
        foreach ($this->_block_dao->getBlockPositions() as $position) {
            $block_list = array();
            $block_list["position"] = $position->getName();
            $block_list["blocks"] = $this->getBlocks($position);
            $block_lists[] = $block_list;
        }
        // blocks without position
        $block_list = array();
        $block_list["position"] = null;
        $block_list["blocks"] = $this->getBlocks(null);
        $block_lists[] = $block_list;
        return $block_lists;
    }

    private function getBlocks(?BlockPosition $position): array {
        $blocks = array();
        if (!is_null($position)) {
            foreach ($this->_block_dao->getBlocksByPosition($position) as $block) {
                $blocks[] = $this->toArray($block);
            }
        } else {
            foreach ($this->_block_dao->getBlocksWithoutPosition() as $block) {
                $blocks[] = $this->toArray($block);
            }
        }
        return $blocks;
    }

    private function toArray(Block $block): array {
        $block_value = array();
        $block_value["id"] = $block->getId();
        $block_value["title"] = $block->getTitle();
        $block_value["published"] = $block->isPublished();
        return $block_value;
    }
}

?>
