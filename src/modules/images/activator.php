<?php

namespace Obcato\Core\modules\images;

use Obcato\Core\core\model\Module;
use Obcato\Core\modules\images\visuals\images\ImagesTab;
use Obcato\Core\modules\images\visuals\import\ImportTab;
use Obcato\Core\view\views\ActionButton;
use Obcato\Core\view\views\ActionButtonAdd;
use Obcato\Core\view\views\ActionButtonDelete;
use Obcato\Core\view\views\ActionButtonSave;
use Obcato\Core\view\views\ModuleVisual;
use Obcato\Core\view\views\TabMenu;

class ImageModuleVisual extends ModuleVisual {

    private static string $HEAD_INCLUDES_TEMPLATE = "images/templates/head_includes.tpl";
    private static int $IMAGES_TAB = 0;
    private static int $IMPORT_TAB = 1;

    private ImageRequestHandler $imageRequestHandler;
    private ImportRequestHandler $importRequestHandler;
    private int $currentTabId;

    public function __construct(Module $module) {
        parent::__construct($module);
        $this->imageRequestHandler = new ImageRequestHandler();
        $this->importRequestHandler = new ImportRequestHandler();
        $this->currentTabId = $this->getCurrentTabId();
    }

    public function getTemplateFilename(): string {
        return "images/templates/root.tpl";
    }

    public function load(): void {
        $content = null;
        if ($this->currentTabId == self::$IMAGES_TAB) {
            $content = new ImagesTab($this->imageRequestHandler);
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
        if ($this->currentTabId == self::$IMPORT_TAB) {
            $actionButtons[] = new ActionButton("Importeren", "upload_zip", "icon_upload");
        }
        return $actionButtons;
    }

    public function renderStyles(): array {
        $styles = array();
        $styles[] = $this->getTemplateEngine()->fetch("images/templates/styles/images.css.tpl");
        return $styles;
    }

    public function renderScripts(): array {
        $scripts = array();
        $scripts[] = $this->getTemplateEngine()->fetch("images/templates/scripts/module_image.js.tpl");
        return $scripts;
    }

    public function getRequestHandlers(): array {
        $requestHandlers = array();
        $requestHandlers[] = $this->imageRequestHandler;
        $requestHandlers[] = $this->importRequestHandler;
        return $requestHandlers;
    }

    public function onRequestHandled(): void {}

    public function loadTabMenu(TabMenu $tabMenu): int {
        $tabMenu->addItem("images_tab_images", self::$IMAGES_TAB);
        $tabMenu->addItem("images_tab_import", self::$IMPORT_TAB);
        return $this->getCurrentTabId();
    }

}