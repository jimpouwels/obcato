<?php

	// No direct access
	defined('_ACCESS') or die;

	require_once CMS_ROOT . "database/dao/template_dao.php";
	require_once CMS_ROOT . "view/views/information_message.php";
	require_once CMS_ROOT . "modules/templates/visuals/scope_selector.php";

	class TemplateList extends Visual {
		
		private static $TEMPLATE_LIST_TEMPLATE = "templates/template_list.tpl";

		private $_template_engine;
		private $_template_dao;
		private $_scope;
		
		public function __construct($scope) {
			$this->_scope = $scope;
			$this->_template_engine = TemplateEngine::getInstance();
			$this->_template_dao = TemplateDao::getInstance();
		}
		
		public function render() {
			$this->_template_engine->assign("scope", $this->_scope->getName());
			$this->_template_engine->assign("templates", $this->getTemplatesForScope($this->_scope));
			$this->_template_engine->assign("information_message", $this->renderInformationMessage());
			return $this->_template_engine->fetch("modules/" . self::$TEMPLATE_LIST_TEMPLATE);
		}
		
		private function getScopeSelector() {
			return new ScopeSelector();
		}
		
		private function getTemplatesForScope($scope) {
			$templates_data = array();
			foreach ($this->_template_dao->getTemplatesByScope($scope) as $template) {
				$template_data = array();
				$template_data["id"] = $template->getId();
				$template_data["name"] = $template->getName();
				$template_data["filename"] = $template->getFileName();
				$template_data["exists"] = $template->exists();
				$template_data["delete_checkbox"] = $this->renderDeleteCheckBox($template);
				$templates_data[] = $template_data;
			}
			return $templates_data;
		}
		
		private function renderDeleteCheckBox($template) {
			$checkbox = new SingleCheckbox("template_" . $template->getId() . "_delete", "", "", false, "");
			return $checkbox->render();
		}
		
		private function renderInformationMessage() {
			$information_message = new InformationMessage("Geen templates gevonden. Klik op 'toevoegen' om een nieuw template te maken.");
			return $information_message->render();
		}
		
	}