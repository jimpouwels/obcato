<?php
require_once CMS_ROOT . "/view/views/ModuleVisual.php";
require_once CMS_ROOT . "/modules/images/visuals/import/ImportTab.php";
require_once CMS_ROOT . "/modules/images/visuals/images/images_tab.php";
require_once CMS_ROOT . "/modules/images/visuals/labels/LabelsTab.php";
require_once CMS_ROOT . "/view/views/TabMenu.php";
require_once CMS_ROOT . "/modules/images/ImageRequestHandler.php";
require_once CMS_ROOT . "/modules/images/LabelRequestHandler.php";
require_once CMS_ROOT . "/modules/images/ImportRequestHandler.php";

class ImageModuleVisual extends ModuleVisual {

    private static string $HEAD_INCLUDES_TEMPLATE = "images/head_includes.tpl";
    private static int $IMAGES_TAB = 0;
    private static int $LABELS_TAB = 1;
    private static int $IMPORT_TAB = 2;

    private ImageRequestHandler $imageRequestHandler;
    private LabelRequestHandler $labelRequestHandler;
    private ImportRequestHandler $importRequestHandler;
    private int $currentTabId;

    public function __construct(Module $module) {
        parent::__construct($module);
        $this->imageRequestHandler = new ImageRequestHandler();
        $this->labelRequestHandler = new LabelRequestHandler();
        $this->importRequestHandler = new ImportRequestHandler();
        $this->currentTabId = $this->getCurrentTabId();
    }

    public function getTemplateFilename(): string {
        return "modules/images/root.tpl";
    }

    public function load(): void {
        $content = null;
        if ($this->currentTabId == self::$IMAGES_TAB) {
            $content = new ImagesTab($this->imageRequestHandler);
        } else if ($this->currentTabId == self::$LABELS_TAB) {
            $content = new LabelsTab($this->labelRequestHandler);
        } else if ($this->currentTabId == self::$IMPORT_TAB) {
            $content = new ImportTab();
        }

        if (!is_null($content)) {
            $this->assign("content", $content->render());
        }
    }

    public function getActionButtons(): array {
        $actionButtons = array();
        if ($this->currentTabId == self::$IMAGES_TAB) {
            $saveButton = null;
            $deleteButton = null;
            if (!is_null($this->imageRequestHandler->getCurrentImage())) {
                $saveButton = new ActionButtonSave('update_image');
                $deleteButton = new ActionButtonDelete('delete_image');
            }
            $actionButtons[] = $saveButton;
            $actionButtons[] = new ActionButtonAdd('add_image');
            $actionButtons[] = $deleteButton;
        }
        if ($this->currentTabId == self::$LABELS_TAB) {
            if (!is_null($this->labelRequestHandler->getCurrentLabel())) {
                $actionButtons[] = new ActionButtonSave('update_label');
            }
            $actionButtons[] = new ActionButtonAdd('add_label');
            $actionButtons[] = new ActionButtonDelete('delete_labels');
        }
        if ($this->currentTabId == self::$IMPORT_TAB) {
            $actionButtons[] = new ActionButton("Importeren", "upload_zip", "icon_upload");
        }
        return $actionButtons;
    }

    public function renderHeadIncludes(): string {
        $this->getTemplateEngine()->assign("path", $this->getModuleIdentifier());
        return $this->getTemplateEngine()->fetch("modules/" . self::$HEAD_INCLUDES_TEMPLATE);
    }

    public function getRequestHandlers(): array {
        $requestHandlers = array();
        $requestHandlers[] = $this->imageRequestHandler;
        $requestHandlers[] = $this->labelRequestHandler;
        $requestHandlers[] = $this->importRequestHandler;
        return $requestHandlers;
    }

    public function onRequestHandled(): void {}

    public function getTabMenu(): ?TabMenu {
        $tabMenu = new TabMenu($this->getCurrentTabId());
        $tabMenu->addItem("images_tab_images", self::$IMAGES_TAB);
        $tabMenu->addItem("images_tab_labels", self::$LABELS_TAB);
        $tabMenu->addItem("images_tab_import", self::$IMPORT_TAB);
        return $tabMenu;
    }

}

?>