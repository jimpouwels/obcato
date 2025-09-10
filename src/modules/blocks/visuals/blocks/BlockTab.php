<?php

namespace Obcato\Core\modules\blocks\visuals\blocks;

use Obcato\Core\modules\blocks\model\Block;
use Obcato\Core\view\views\Visual;

class BlockTab extends Visual {

    private ?Block $currentBlock;

    public function __construct(?Block $current) {
        parent::__construct();
        $this->currentBlock = $current;
    }

    public function getTemplateFilename(): string {
        return "blocks/templates/blocks/root.tpl";
    }

    public function load(): void {
        $this->assign("blocks_list", $this->renderBlocksList());
        if ($this->currentBlock) {
            $this->assign("editor", $this->renderBlockEditor());
        }
    }

    private function renderBlocksList(): string {
        return (new BlocksList($this->currentBlock))->render();
    }

    private function renderBlockEditor(): string {
        return (new BlockEditor($this->currentBlock))->render();
    }

}