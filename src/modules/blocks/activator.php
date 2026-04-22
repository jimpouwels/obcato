<?php

namespace Pageflow\Core\modules\blocks;

use Pageflow\Core\core\model\Module;
use Pageflow\Core\modules\blocks\model\Block;
use Pageflow\Core\modules\blocks\model\BlockPosition;
use Pageflow\Core\modules\blocks\visuals\blocks\BlockTab;
use Pageflow\Core\modules\blocks\visuals\positions\PositionTab;
use Pageflow\Core\view\views\ActionButtonAdd;
use Pageflow\Core\view\views\ActionButtonDelete;
use Pageflow\Core\view\views\ActionButtonSave;
use Pageflow\Core\view\views\ModuleVisual;
use Pageflow\Core\view\views\TabMenu;

class BlockModuleVisual extends ModuleVisual {

    private static string $HEAD_INCLUDES_TEMPLATE = "blocks/templates/head_includes.tpl";
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
        return "blocks/templates/root.tpl";
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

    public function renderStyles(): array {
        $styles = array();
        
        // Add element statics styles
        if ($this->currentBlock) {
            $elementStatics = $this->currentBlock->getElementStatics();
            foreach ($elementStatics as $elementStatic) {
                $elementStyles = $elementStatic->renderStyles();
                $styles = array_merge($styles, $elementStyles);
            }
        }
        
        // Render module CSS
        $styles[] = $this->getTemplateEngine()->fetch("blocks/templates/styles/blocks.css.tpl");
        
        return $styles;
    }

    public function renderScripts(): array {
        $scripts = array();
        
        // Add element statics scripts
        if ($this->currentBlock) {
            $elementStatics = $this->currentBlock->getElementStatics();
            foreach ($elementStatics as $elementStatic) {
                $elementScripts = $elementStatic->renderScripts();
                $scripts = array_merge($scripts, $elementScripts);
            }
        }
        
        // Render module JS
        $scripts[] = $this->getTemplateEngine()->fetch("blocks/templates/scripts/module_blocks.js.tpl");
        $scripts[] = $this->getTemplateEngine()->fetch("scripts/element_holder_version_check.js.tpl");
        
        return $scripts;
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

    public function isElementHolder(): bool {
        return $this->getCurrentTabId() == self::$BLOCKS_TAB;
    }

}