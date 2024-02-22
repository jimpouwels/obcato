<?php

namespace Obcato\Core\admin\modules\blocks;

use Obcato\Core\admin\core\model\Module;
use Obcato\Core\admin\modules\blocks\model\Block;
use Obcato\Core\admin\modules\blocks\model\BlockPosition;
use Obcato\Core\admin\modules\blocks\visuals\blocks\BlockTab;
use Obcato\Core\admin\modules\blocks\visuals\positions\PositionTab;
use Obcato\Core\admin\view\views\ActionButtonAdd;
use Obcato\Core\admin\view\views\ActionButtonDelete;
use Obcato\Core\admin\view\views\ActionButtonSave;
use Obcato\Core\admin\view\views\ModuleVisual;
use Obcato\Core\admin\view\views\TabMenu;

class BlockModuleVisual extends ModuleVisual {

    private static string $HEAD_INCLUDES_TEMPLATE = "blocks/head_includes.tpl";
    private static int $BLOCKS_TAB = 0;
    private static int $POSITIONS_TAB = 1;
    private ?Block $currentBlock = null;
    private ?BlockPosition $currentPosition = null;
    private Module $module;
    private BlockRequestHandler $blockRequestHandler;
    private PositionRequestHandler $positionRequestHandler;

    public function __construct(Module $module) {
        parent::__construct($module);
        $this->module = $module;
        $this->blockRequestHandler = new BlockRequestHandler();
        $this->positionRequestHandler = new PositionRequestHandler();
    }

    public function getTemplateFilename(): string {
        return "modules/blocks/root.tpl";
    }

    public function load(): void {
        $content = null;
        if ($this->getCurrentTabId() == self::$BLOCKS_TAB) {
            $content = new BlockTab($this->currentBlock);
        } else if ($this->getCurrentTabId() == self::$POSITIONS_TAB) {
            $content = new PositionTab($this->currentPosition);
        }
        if ($content) {
            $this->assign("content", $content->render());
        }
    }

    public function getActionButtons(): array {
        $actionButtons = array();
        if ($this->getCurrentTabId() == self::$BLOCKS_TAB) {
            if ($this->currentBlock) {
                $actionButtons[] = new ActionButtonSave('update_element_holder');
                $actionButtons[] = new ActionButtonDelete('delete_element_holder');
            }
            $actionButtons[] = new ActionButtonAdd('add_element_holder');
        }
        if ($this->getCurrentTabId() == self::$POSITIONS_TAB) {
            if ($this->currentPosition || PositionTab::isEditPositionMode()) {
                $actionButtons[] = new ActionButtonSave('update_position');
            }
            $actionButtons[] = new ActionButtonAdd('add_position');
            $actionButtons[] = new ActionButtonDelete('delete_positions');
        }
        return $actionButtons;
    }

    public function renderHeadIncludes(): string {
        $this->getTemplateEngine()->assign("path", $this->module->getIdentifier());
        $elementStaticsValues = array();
        if ($this->currentBlock) {
            $elementStatics = $this->currentBlock->getElementStatics();
            foreach ($elementStatics as $element_static) {
                $elementStaticsValues[] = $element_static->render();
            }
        }
        $this->getTemplateEngine()->assign("element_statics", $elementStaticsValues);
        return $this->getTemplateEngine()->fetch("modules/" . self::$HEAD_INCLUDES_TEMPLATE);
    }

    public function getRequestHandlers(): array {
        $requestHandlers = array();
        $requestHandlers[] = $this->blockRequestHandler;
        $requestHandlers[] = $this->positionRequestHandler;
        return $requestHandlers;
    }

    public function onRequestHandled(): void {
        $this->currentBlock = $this->blockRequestHandler->getCurrentBlock();
        $this->currentPosition = $this->positionRequestHandler->getCurrentPosition();
    }

    public function loadTabMenu(TabMenu $tabMenu): int {
        $tabMenu->addItem("blocks_tabmenu_blocks", self::$BLOCKS_TAB);
        $tabMenu->addItem("blocks_tabmenu_positions", self::$POSITIONS_TAB);
        return $this->getCurrentTabId();
    }

}