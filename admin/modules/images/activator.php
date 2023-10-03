<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/view/views/module_visual.php";
require_once CMS_ROOT . "/modules/images/visuals/import/ImportTab.php";
require_once CMS_ROOT . "/modules/images/visuals/images/images_tab.php";
require_once CMS_ROOT . "/modules/images/visuals/labels/LabelsTab.php";
require_once CMS_ROOT . "/view/views/tab_menu.php";
require_once CMS_ROOT . "/modules/images/ImageRequestHandler.php";
require_once CMS_ROOT . "/modules/images/LabelRequestHandler.php";
require_once CMS_ROOT . "/modules/images/ImportRequestHandler.php";

class ImageModuleVisual extends ModuleVisual {

    private static string $HEAD_INCLUDES_TEMPLATE = "images/head_includes.tpl";
    private static int $IMAGES_TAB = 0;
    private static int $LABELS_TAB = 1;
    private static int $IMPORT_TAB = 2;

    private ImageRequestHandler $_images_request_handler;
    private LabelRequestHandler $_label_request_handler;
    private ImportRequestHandler $_import_request_handler;
    private Module $_image_module;
    private int $_current_tab_id;

    public function __construct(Module $image_module) {
        parent::__construct($image_module);
        $this->_image_module = $image_module;
        $this->_images_request_handler = new ImageRequestHandler();
        $this->_label_request_handler = new LabelRequestHandler();
        $this->_import_request_handler = new ImportRequestHandler();
        $this->_current_tab_id = $this->getCurrentTabId();
    }

    public function getTemplateFilename(): string {
        return "modules/images/root.tpl";
    }

    public function load(): void {
        $content = null;
        if ($this->_current_tab_id == self::$IMAGES_TAB) {
            $content = new ImagesTab($this->_images_request_handler);
        } else if ($this->_current_tab_id == self::$LABELS_TAB) {
            $content = new LabelsTab($this->_label_request_handler);
        } else if ($this->_current_tab_id == self::$IMPORT_TAB) {
            $content = new ImportTab();
        }

        if (!is_null($content)) {
            $this->assign("content", $content->render());
        }
    }

    public function getActionButtons(): array {
        $action_buttons = array();
        if ($this->_current_tab_id == self::$IMAGES_TAB) {
            $save_button = null;
            $delete_button = null;
            if (!is_null($this->_images_request_handler->getCurrentImage())) {
                $save_button = new ActionButtonSave('update_image');
                $delete_button = new ActionButtonDelete('delete_image');
            }
            $action_buttons[] = $save_button;
            $action_buttons[] = new ActionButtonAdd('add_image');
            $action_buttons[] = $delete_button;
        }
        if ($this->_current_tab_id == self::$LABELS_TAB) {
            if (!is_null($this->_label_request_handler->getCurrentLabel())) {
                $action_buttons[] = new ActionButtonSave('update_label');
            }
            $action_buttons[] = new ActionButtonAdd('add_label');
            $action_buttons[] = new ActionButtonDelete('delete_labels');
        }
        if ($this->_current_tab_id == self::$IMPORT_TAB) {
            $action_buttons[] = new ActionButton("Importeren", "upload_zip", "icon_upload");
        }
        return $action_buttons;
    }

    public function renderHeadIncludes(): string {
        $this->getTemplateEngine()->assign("path", $this->_image_module->getIdentifier());
        return $this->getTemplateEngine()->fetch("modules/" . self::$HEAD_INCLUDES_TEMPLATE);
    }

    public function getRequestHandlers(): array {
        $request_handlers = array();
        $request_handlers[] = $this->_images_request_handler;
        $request_handlers[] = $this->_label_request_handler;
        $request_handlers[] = $this->_import_request_handler;
        return $request_handlers;
    }

    public function onRequestHandled(): void {}

    public function getTabMenu(): ?TabMenu {
        $tab_menu = new TabMenu($this->getCurrentTabId());
        $tab_menu->addItem("images_tab_images", self::$IMAGES_TAB);
        $tab_menu->addItem("images_tab_labels", self::$LABELS_TAB);
        $tab_menu->addItem("images_tab_import", self::$IMPORT_TAB);
        return $tab_menu;
    }

}

?>