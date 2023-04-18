<?php
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/module_visual.php";
    require_once CMS_ROOT . "modules/images/visuals/import/import_tab.php";
    require_once CMS_ROOT . "modules/images/visuals/images/images_tab.php";
    require_once CMS_ROOT . "modules/images/visuals/labels/labels_tab.php";
    require_once CMS_ROOT . "view/views/tab_menu.php";
    require_once CMS_ROOT . "modules/images/image_pre_handler.php";
    require_once CMS_ROOT . "modules/images/label_pre_handler.php";
    require_once CMS_ROOT . "modules/images/import_pre_handler.php";

    class ImageModuleVisual extends ModuleVisual {
    
        private static $TEMPLATE = "images/root.tpl";
        private static $HEAD_INCLUDES_TEMPLATE = "images/head_includes.tpl";
        private static $IMAGES_TAB = 0;
        private static $LABELS_TAB = 1;
        private static $IMPORT_TAB = 2;

        private $_image_dao;
        private $_images_pre_handler;
        private $_label_pre_handler;
        private $_import_pre_handler;
        private $_image_module;
        private $_current_tab_id;
        
        public function __construct($image_module) {
            parent::__construct($image_module);
            $this->_image_module = $image_module;
            $this->_image_dao = ImageDao::getInstance();
            $this->_images_pre_handler = new ImagePreHandler();
            $this->_label_pre_handler = new LabelPreHandler();
            $this->_import_pre_handler = new ImportPreHandler();
            $this->_current_tab_id = $this->getCurrentTabId();
        }
        
        public function render(): string {
            $this->getTemplateEngine()->assign("tab_menu", $this->renderTabMenu());
            $content = null;
            if ($this->_current_tab_id == self::$IMAGES_TAB) {
                $content = new ImagesTab($this->_images_pre_handler);
            } else if ($this->_current_tab_id == self::$LABELS_TAB) {
                $content = new LabelsTab($this->_label_pre_handler);
            } else if ($this->_current_tab_id == self::$IMPORT_TAB) {
                $content = new ImportTab();
            }
            
            if (!is_null($content)) {
                $this->getTemplateEngine()->assign("content", $content->render());
            }
            
            return $this->getTemplateEngine()->fetch("modules/" . self::$TEMPLATE);
        }
    
        public function getActionButtons() {
            $action_buttons = array();
            if ($this->_current_tab_id == self::$IMAGES_TAB) {
                $save_button = null;
                $delete_button = null;
                if (!is_null($this->_images_pre_handler->getCurrentImage())) {
                    $save_button = new ActionButtonSave('update_image');
                    $delete_button = new ActionButtonDelete('delete_image');
                }
                $action_buttons[] = $save_button;
                $action_buttons[] = new ActionButtonAdd('add_image');
                $action_buttons[] = $delete_button;                
            }
            if ($this->_current_tab_id == self::$LABELS_TAB) {
                if (!is_null($this->_label_pre_handler->getCurrentLabel())) {
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
        
        public function renderHeadIncludes() {
            $this->getTemplateEngine()->assign("path", $this->_image_module->getIdentifier());
            return $this->getTemplateEngine()->fetch("modules/" . self::$HEAD_INCLUDES_TEMPLATE);
        }
        
        public function getRequestHandlers() {
            $request_handlers = array();
            $request_handlers[] = $this->_images_pre_handler;
            $request_handlers[] = $this->_label_pre_handler;
            $request_handlers[] = $this->_import_pre_handler;
            return $request_handlers;
        }
        
        public function onRequestHandled(): void {
        }
        
        private function renderTabMenu() {
            $tab_items = array();
            
            $tab_item = array();
            $tab_item["text"] = $this->getTextResource("images_tab_images");
            $tab_item["id"] = self::$IMAGES_TAB;
            $tab_items[] = $tab_item;

            $tab_item = array();
            $tab_item["text"] = $this->getTextResource("images_tab_labels");
            $tab_item["id"] = self::$LABELS_TAB;
            $tab_items[] = $tab_item;

            $tab_item = array();
            $tab_item["text"] = $this->getTextResource("images_tab_target_pages");
            $tab_item["id"] = self::$IMPORT_TAB;
            $tab_items[] = $tab_item;
            
            $tab_menu = new TabMenu($tab_items, BlackBoard::$MODULE_TAB_ID);
            return $tab_menu->render();
        }
    
    }
    
?>