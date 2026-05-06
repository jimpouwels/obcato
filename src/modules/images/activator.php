<?php

namespace Pageflow\Core\modules\images;

use Pageflow\Core\core\model\Module;
use Pageflow\Core\modules\images\visuals\functional\FunctionalImagesTab;
use Pageflow\Core\modules\images\visuals\images\ImagesTab;
use Pageflow\Core\modules\images\visuals\import\ImportTab;
use Pageflow\Core\view\views\ActionButton;
use Pageflow\Core\view\views\ActionButtonAdd;
use Pageflow\Core\view\views\ActionButtonAddFolder;
use Pageflow\Core\view\views\ActionButtonDelete;
use Pageflow\Core\view\views\ActionButtonSave;
use Pageflow\Core\view\views\ModuleVisual;
use Pageflow\Core\view\views\TabMenu;

class ImageModuleVisual extends ModuleVisual {

    private static string $HEAD_INCLUDES_TEMPLATE = "images/templates/head_includes.tpl";
    private static int $IMAGES_TAB = 0;
    private static int $IMPORT_TAB = 1;
    private static int $FUNCTIONAL_IMAGES_TAB = 2;

    private ImageRequestHandler $imageRequestHandler;
    private ImportRequestHandler $importRequestHandler;
    private FunctionalImageRequestHandler $functionalImageRequestHandler;
    private int $currentTabId;

    public function __construct(Module $module) {
        parent::__construct($module);
        $this->imageRequestHandler = new ImageRequestHandler();
        $this->importRequestHandler = new ImportRequestHandler();
        $this->functionalImageRequestHandler = new FunctionalImageRequestHandler();
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
        } else if ($this->currentTabId == self::$FUNCTIONAL_IMAGES_TAB) {
            $content = new FunctionalImagesTab($this->functionalImageRequestHandler);
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
        if ($this->currentTabId == self::$FUNCTIONAL_IMAGES_TAB) {
            $currentImage  = $this->functionalImageRequestHandler->getCurrentImage();
            $currentFolder = $this->functionalImageRequestHandler->getCurrentFolder();
            if ($currentImage) {
                $actionButtons[] = new ActionButtonSave('update_fimg');
                $actionButtons[] = new ActionButtonDelete('delete_fimg');
            } elseif ($currentFolder) {
                $actionButtons[] = new ActionButtonSave('update_fimg_folder');
                $actionButtons[] = new ActionButtonDelete('delete_fimg_folder');
            }
            $actionButtons[] = new ActionButtonAdd('add_fimg');
            $actionButtons[] = new ActionButtonAddFolder('add_fimg_folder');
        }
        return $actionButtons;
    }

    public function renderStyles(): array {
        $styles = array();
        $styles[] = $this->getTemplateEngine()->fetch("images/templates/styles/images.css.tpl");
        if ($this->currentTabId == self::$FUNCTIONAL_IMAGES_TAB) {
            $styles[] = $this->getTemplateEngine()->fetch("images/templates/functional/styles/module_fimg.css.tpl");
        }
        return $styles;
    }

    public function renderScripts(): array {
        $scripts = array();
        $scripts[] = $this->getTemplateEngine()->fetch("images/templates/scripts/module_image.js.tpl");
        if ($this->currentTabId == self::$FUNCTIONAL_IMAGES_TAB) {
            $scripts[] = $this->getTemplateEngine()->fetch("images/templates/functional/scripts/module_fimg.js.tpl");
        }
        return $scripts;
    }

    public function getRequestHandlers(): array {
        $requestHandlers = array();
        $requestHandlers[] = $this->imageRequestHandler;
        $requestHandlers[] = $this->importRequestHandler;
        $requestHandlers[] = $this->functionalImageRequestHandler;
        return $requestHandlers;
    }

    public function onRequestHandled(): void {}

    public function loadTabMenu(TabMenu $tabMenu): int {
        $tabMenu->addItem("images_tab_images", self::$IMAGES_TAB);
        $tabMenu->addItem("images_tab_import", self::$IMPORT_TAB);
        $tabMenu->addItem("images_tab_functional", self::$FUNCTIONAL_IMAGES_TAB);
        return $this->getCurrentTabId();
    }

}