<?php

namespace Obcato\Core\admin\modules\blocks\visuals\blocks;

use Obcato\ComponentApi\TemplateEngine;
use Obcato\ComponentApi\Visual;
use Obcato\Core\admin\modules\blocks\model\Block;

class BlockTab extends Visual {

    private ?Block $currentBlock;

    public function __construct(TemplateEngine $templateEngine, ?Block $current) {
        parent::__construct($templateEngine);
        $this->currentBlock = $current;
    }

    public function getTemplateFilename(): string {
        return "modules/blocks/blocks/root.tpl";
    }

    public function load(): void {
        $this->assign("blocks_list", $this->renderBlocksList());
        if ($this->currentBlock) {
            $this->assign("editor", $this->renderBlockEditor());
        }
    }

    private function renderBlocksList(): string {
        return (new BlocksList($this->getTemplateEngine(), $this->currentBlock))->render();
    }

    private function renderBlockEditor(): string {
        return (new BlockEditor($this->getTemplateEngine(), $this->currentBlock))->render();
    }

}