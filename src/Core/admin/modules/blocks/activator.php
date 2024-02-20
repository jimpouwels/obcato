<?php

namespace Obcato\Core;

use Obcato\ComponentApi\ModuleVisual;
use Obcato\ComponentApi\TabMenu;
use Obcato\ComponentApi\TemplateEngine;
use Obcato\Core\admin\core\model\Module;

require_once CMS_ROOT . "/view/views/TabMenu.php";
require_once CMS_ROOT . "/database/dao/BlockDaoMysql.php";
require_once CMS_ROOT . "/modules/blocks/visuals/blocks/BlockTab.php";
require_once CMS_ROOT . "/modules/blocks/visuals/positions/PositionTab.php";
require_once CMS_ROOT . "/modules/blocks/BlockRequestHandler.php";
require_once CMS_ROOT . "/modules/blocks/PositionRequestHandler.php";

class BlockModuleVisual extends ModuleVisual {

    private static string $HEAD_INCLUDES_TEMPLATE = "blocks/head_includes.tpl";
    private static int $BLOCKS_TAB = 0;
    private static int $POSITIONS_TAB = 1;
    private ?Block $currentBlock = null;
    private ?BlockPosition $currentPosition = null;
    private Module $module;
    private BlockRequestHandler $blockRequestHandler;
    private PositionRequestHandler $positionRequestHandler;

    public function __construct(TemplateEngine $templateEngine, Module $module) {
        parent::__construct($templateEngine, $module);
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
            $content = new BlockTab($this->getTemplateEngine(), $this->currentBlock);
        } else if ($this->getCurrentTabId() == self::$POSITIONS_TAB) {
            $content = new PositionTab($this->getTemplateEngine(), $this->currentPosition);
        }
        if ($content) {
            $this->assign("content", $content->render());
        }
    }

    public function getActionButtons(): array {
        $actionButtons = array();
        if ($this->getCurrentTabId() == self::$BLOCKS_TAB) {
            if ($this->currentBlock) {
                $actionButtons[] = new ActionButtonSave($this->getTemplateEngine(), 'update_element_holder');
                $actionButtons[] = new ActionButtonDelete($this->getTemplateEngine(), 'delete_element_holder');
            }
            $actionButtons[] = new ActionButtonAdd($this->getTemplateEngine(), 'add_element_holder');
        }
        if ($this->getCurrentTabId() == self::$POSITIONS_TAB) {
            if ($this->currentPosition || PositionTab::isEditPositionMode()) {
                $actionButtons[] = new ActionButtonSave($this->getTemplateEngine(), 'update_position');
            }
            $actionButtons[] = new ActionButtonAdd($this->getTemplateEngine(), 'add_position');
            $actionButtons[] = new ActionButtonDelete($this->getTemplateEngine(), 'delete_positions');
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

    public function loadTabMenu(TabMenu $tabMenu): void {
        $tabMenu->addItem("blocks_tabmenu_blocks", self::$BLOCKS_TAB);
        $tabMenu->addItem("blocks_tabmenu_positions", self::$POSITIONS_TAB);
    }

}