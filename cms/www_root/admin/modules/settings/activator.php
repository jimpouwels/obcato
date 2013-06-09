<?php

	// No direct access
	defined('_ACCESS') or die;
	
	require_once "core/data/settings.php";
	require_once "core/visual/module_visual.php";
	require_once "core/visual/action_button.php";
	require_once "core/visual/warning_message.php";
	require_once "libraries/system/template_engine.php";
	require_once "modules/settings/visuals/settings_editor.php";
	require_once "modules/settings/settings_pre_handler.php";

	class SettingsModuleVisual extends ModuleVisual {
	
		private static $TEMPLATE = "settings/module_settings.tpl";
		private static $HEAD_INCLUDES_TEMPLATE = "modules/settings/head_includes.tpl";
		private $_templage_engine;
		private $_settings_module;
		private $_settings_pre_handler;
	
		public function __construct($settings_module) {
			$this->_settings_module = $settings_module;
			$this->_template_engine = TemplateEngine::getInstance();
			$this->_settings_pre_handler = new SettingsPreHandler();
		}
	
		public function render() {
			$this->_template_engine->assign("warning_message", $this->renderWarningMessage());
			$this->_template_engine->assign("settings_editor", $this->renderSettingsEditor());
			return $this->_template_engine->fetch("modules/" . self::$TEMPLATE);
		}
		
		public function getTitle() {
			$this->_settings_module->getTitle();
		}
	
		public function getActionButtons() {
			$action_buttons = array();
			$action_buttons[] = new ActionButton("Opslaan", "apply_settings", "icon_apply");
			
			return $action_buttons;
		}
		
		public function getHeadIncludes() {
			$this->_template_engine->assign("path", $this->_settings_module->getIdentifier());			
			return $this->_template_engine->fetch(self::$HEAD_INCLUDES_TEMPLATE);
		}
		
		public function preHandle() {
			$this->_settings_pre_handler->handle();
		}
		
		private function renderSettingsEditor() {
			$settings_editor = new SettingsEditor(Settings::find());
			return $settings_editor->render();
		}
		
		private function renderWarningMessage() {
			$warning_message = new WarningMessage("Let op! Het incorrect wijzigen van de onderstaande instellingen kan zorgen voor een niet (goed) meer werkende website!");
			return $warning_message->render();
		}
	
	}
	
?>