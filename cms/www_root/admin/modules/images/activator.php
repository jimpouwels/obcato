<?php

	// No direct access
	defined('_ACCESS') or die;
	
	require_once "core/visual/module_visual.php";
	require_once "core/visual/action_button.php";
	require_once "libraries/system/template_engine.php";
	require_once "modules/images/visuals/images/image_list.php";
	require_once "modules/images/visuals/images/images_tab.php";
	require_once "core/visual/tab_menu.php";
	require_once "modules/images/image_pre_handler.php";
	require_once "modules/images/label_pre_handler.php";

	class ImageModuleVisual extends ModuleVisual {
	
		private static $TEMPLATE = "images/root.tpl";
		private static $HEAD_INCLUDES_TEMPLATE = "images/head_includes.tpl";
		private static $IMAGES_TAB = 0;
		private static $LABELS_TAB = 1;
		private static $IMPORT_TAB = 2;

		private $_template_engine;
		private $_image_dao;
		private $_images_pre_handler;
		private $_label_pre_handler;
		private $_image_module;
		private $_current_tab_id;
		
		public function __construct($image_module) {
			$this->_image_module = $image_module;
			$this->_template_engine = TemplateEngine::getInstance();
			$this->_image_dao = ImageDao::getInstance();
			$this->_images_pre_handler = new ImagePreHandler();
			$this->_label_pre_handler = new LabelPreHandler();
			$this->_current_tab_id = $this->_images_pre_handler->getCurrentTabId();
		}
		
		public function render() {
			$this->_template_engine->assign("tab_menu", $this->renderTabMenu());
			
			$content = null;
			if ($this->_current_tab_id == self::$IMAGES_TAB) {
				$content = new ImagesTab($this->_label_pre_handler->getCurrentLabelFromGetRequest(), $this->_images_pre_handler);
			} else if ($this->_current_tab_id == self::$LABELS_TAB) {
				$content = new LabelManager($this->_images_pre_handler->getCurrentLabelFromGetRequest());
			} else if ($this->_current_tab_id == self::$IMPORT_TAB) {
			}
			
			if (!is_null($content)) {
				$this->_template_engine->assign("content", $content->render());
			}
			
			return $this->_template_engine->fetch("modules/" . self::$TEMPLATE);
		}
		
		public function getTitle() {
			return $this->_image_module->getTitle();
		}
	
		public function getActionButtons() {
			$action_buttons = array();
			if ($this->_current_tab_id == self::$IMAGES_TAB) {
				$save_button = null;
				$delete_button = null;
				if (!is_null($this->_images_pre_handler->getCurrentImageFromGetRequest())) {
					$save_button = new ActionButton("Opslaan", "update_image", "icon_apply");
					$delete_button = new ActionButton("Verwijderen", "delete_image", "icon_delete");
				}
				$action_buttons[] = $save_button;
				$action_buttons[] = new ActionButton("Toevoegen", "add_image", "icon_add");
				$action_buttons[] = $delete_button;				
			}
			if ($this->_current_tab_id == self::$LABELS_TAB) {
				
			}
			if ($this->_current_tab_id == self::$IMPORT_TAB) {
				
			}
			return $action_buttons;
		}
		
		public function getHeadIncludes() {
			$this->_template_engine->assign("path", $this->_image_module->getIdentifier());
			return $this->_template_engine->fetch("modules/" . self::$HEAD_INCLUDES_TEMPLATE);
		}
		
		public function preHandle() {
			$this->_images_pre_handler->handle();
			$this->_label_pre_handler->handle();
		}
		
		private function renderTabMenu() {
			$tab_items = array();
			$tab_items[self::$IMAGES_TAB] = "Afbeeldingen";
			$tab_items[self::$LABELS_TAB] = "Labels";
			$tab_items[self::$IMPORT_TAB] = "Import";
			$tab_menu = new TabMenu($tab_items, $this->_current_tab_id);
			return $tab_menu->render();
		}
	
	}
	
?>