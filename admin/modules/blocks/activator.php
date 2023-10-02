<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/view/views/module_visual.php";
require_once CMS_ROOT . "/view/views/tab_menu.php";
require_once CMS_ROOT . "/database/dao/BlockDaoMysql.php";
require_once CMS_ROOT . "/modules/blocks/visuals/blocks/block_tab.php";
require_once CMS_ROOT . "/modules/blocks/visuals/positions/position_tab.php";
require_once CMS_ROOT . "/modules/blocks/block_request_handler.php";
require_once CMS_ROOT . "/modules/blocks/position_request_handler.php";

class BlockModuleVisual extends ModuleVisual {

    private static string $HEAD_INCLUDES_TEMPLATE = "blocks/head_includes.tpl";
    private static int $BLOCKS_TAB = 0;
    private static int $POSITIONS_TAB = 1;
    private ?Block $_current_block = null;
    private ?BlockPosition $_current_position = null;
    private Module $_block_module;
    private BlockRequestHandler $_block_request_handler;
    private PositionRequestHandler $_position_request_handler;

    public function __construct(Module $block_module) {
        parent::__construct($block_module);
        $this->_block_module = $block_module;
        $this->_block_request_handler = new BlockRequestHandler();
        $this->_position_request_handler = new PositionRequestHandler();
    }

    public function getTemplateFilename(): string {
        return "modules/blocks/root.tpl";
    }

    public function load(): void {
        $content = null;
        if ($this->getCurrentTabId() == self::$BLOCKS_TAB) {
            $content = new BlockTab($this->_current_block);
        } else if ($this->getCurrentTabId() == self::$POSITIONS_TAB) {
            $content = new PositionTab($this->_current_position);
        }
        if (!is_null($content)) {
            $this->assign("content", $content->render());
        }
    }

    public function getActionButtons(): array {
        $action_buttons = array();
        if ($this->getCurrentTabId() == self::$BLOCKS_TAB) {
            if (!is_null($this->_current_block)) {
                $action_buttons[] = new ActionButtonSave('update_element_holder');
                $action_buttons[] = new ActionButtonDelete('delete_element_holder');
            }
            $action_buttons[] = new ActionButtonAdd('add_element_holder');
        }
        if ($this->getCurrentTabId() == self::$POSITIONS_TAB) {
            if (!is_null($this->_current_position) || PositionTab::isEditPositionMode()) {
                $action_buttons[] = new ActionButtonSave('update_position');
            }
            $action_buttons[] = new ActionButtonAdd('add_position');
            $action_buttons[] = new ActionButtonDelete('delete_positions');
        }
        return $action_buttons;
    }

    public function renderHeadIncludes(): string {
        $this->getTemplateEngine()->assign("path", $this->_block_module->getIdentifier());
        $element_statics_values = array();
        if (!is_null($this->_current_block)) {
            $element_statics = $this->_current_block->getElementStatics();
            foreach ($element_statics as $element_static) {
                $element_statics_values[] = $element_static->render();
            }
        }
        $this->getTemplateEngine()->assign("element_statics", $element_statics_values);
        return $this->getTemplateEngine()->fetch("modules/" . self::$HEAD_INCLUDES_TEMPLATE);
    }

    public function getRequestHandlers(): array {
        $pre_handlers = array();
        $pre_handlers[] = $this->_block_request_handler;
        $pre_handlers[] = $this->_position_request_handler;
        return $pre_handlers;
    }

    public function onRequestHandled(): void {
        $this->_current_block = $this->_block_request_handler->getCurrentBlock();
        $this->_current_position = $this->_position_request_handler->getCurrentPosition();
    }

    public function getTabMenu(): ?TabMenu {
        $tab_menu = new TabMenu($this->getCurrentTabId());
        $tab_menu->addItem("blocks_tabmenu_blocks", self::$BLOCKS_TAB);
        $tab_menu->addItem("blocks_tabmenu_positions", self::$POSITIONS_TAB);
        return $tab_menu;
    }

}

?>