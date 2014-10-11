<?php
	
	defined('_ACCESS') or die;

	require_once CMS_ROOT . "database/dao/template_dao.php";
	require_once CMS_ROOT . "database/dao/scope_dao.php";
	require_once CMS_ROOT . "libraries/system/notifications.php";
	require_once CMS_ROOT . "view/request_handlers/module_request_handler.php";
	require_once CMS_ROOT . "libraries/system/notifications.php";
	require_once CMS_ROOT . "modules/templates/template_form.php";
	
	class TemplatePreHandler extends ModuleRequestHandler {
	
		private static $TEMPLATE_ID_GET = "template";
		private static $SCOPE_NAME_GET = "scope";
		private static $TEMPLATE_ID_POST = "template_id";

		private $_template_dao;
		private $_scope_dao;
		private $_current_template;
		private $_current_scope;
		
		public function __construct() {
			$this->_template_dao = TemplateDao::getInstance();
			$this->_scope_dao = ScopeDao::getInstance();
		}
	
		public function handleGet() {
			if ($this->isCurrentTemplateShown()) {
				$this->_current_template = $this->getTemplateFromGetRequest();
			}
			$this->_current_scope = $this->getScopeFromGetRequest();
		}
		
		public function handlePost() {
			$this->_current_template = $this->getTemplateFromPostRequest();
			$this->_current_scope = $this->getScopeFromGetRequest();
			if ($this->isUpdateAction()) {
				$this->updateTemplate();
			} else if ($this->isAddTemplateAction()) {
				$this->addTemplate();
			} else if ($this->isDeleteAction()) {
				$this->deleteTemplates();
			}
		}
		
		public function getCurrentTemplate() {
			return $this->_current_template;
		}
		
		public function getCurrentScope() {
			return $this->_current_scope;
		}
		
		private function addTemplate() {
			$new_template = $this->_template_dao->createTemplate();
			Notifications::setSuccessMessage("Template succesvol aangemaakt");
			header("Location: /admin/index.php?template=" . $new_template->getId());
			exit();
		}
		
		private function deleteTemplates() {
			foreach ($this->_template_dao->getTemplates() as $template) {
				if (isset($_POST["template_" . $template->getId() . "_delete"])) {
					$this->_template_dao->deleteTemplate($template);
				}
			}
			Notifications::setSuccessMessage("Template(s) succesvol verwijderd");
		}
		
		private function updateTemplate() {
			$template_form = new TemplateForm($this->_current_template);
			$old_file_path = FRONTEND_TEMPLATE_DIR . "\\" . $this->_current_template->getFileName();
			$old_file_name = $this->_current_template->getFileName();
			try {
				$template_form->loadFields();
				if ($template_form->isFileUploaded()) {
					$this->removeOldFile($old_file_path);
					$this->copyUploadToTemplateDir($template_form->getPathToUploadedFile());
				} else if ($this->isTemplateRenamed($old_file_name) && file_exists($old_file_path) ) {
					$this->renameTemplateFile($old_file_name);
				}
				$this->_template_dao->updateTemplate($this->_current_template);
				Notifications::setSuccessMessage("Template succesvol opgeslagen");
			} catch (FormException $e) {
				Notifications::setFailedMessage("Template niet opgeslagen, verwerk de fouten");
			}
		}
		
		private function renameTemplateFile($old_file_name) {
			rename(FRONTEND_TEMPLATE_DIR . "/" . $old_file_name, FRONTEND_TEMPLATE_DIR . "/" . $this->_current_template->getFileName());
		}
		
		private function isTemplateRenamed($old_file_name) {
			return ($old_file_name != "" && $old_file_name != $this->_current_template->getFileName());
		}
		
		private function removeOldFile($old_file_path) {
			if (file_exists($old_file_path)) {
				unlink($old_file_path);
			}
		}
		
		private function copyUploadToTemplateDir($path_to_uploaded_file) {
			move_uploaded_file($path_to_uploaded_file, FRONTEND_TEMPLATE_DIR . "/" . $this->_current_template->getFileName());
		}

		private function getTemplateFromPostRequest() {
			$template = null;
			if (isset($_POST[self::$TEMPLATE_ID_POST])) {
				$template = $this->_template_dao->getTemplate($_POST[self::$TEMPLATE_ID_POST]);
			}
			return $template;
		}
		
		private function getTemplateFromGetRequest() {
			return $this->_template_dao->getTemplate($_GET[self::$TEMPLATE_ID_GET]);
		}
		
		private function getScopeFromGetRequest() {
			if (isset($_GET[self::$SCOPE_NAME_GET])) {
				$scope_name = $_GET[self::$SCOPE_NAME_GET];
				return $this->_scope_dao->getScopeByName($scope_name);
			}
		}
		
		private function isCurrentTemplateShown() {
			return isset($_GET[self::$TEMPLATE_ID_GET]);
		}
		
		private function isUpdateAction() {
			return isset($_POST["action"]) && $_POST["action"] == "update_template";
		}
		
		private function isAddTemplateAction() {
			return isset($_POST["action"]) && $_POST["action"] == "add_template";
		}
		
		private function isDeleteAction() {
			return isset($_POST["action"]) && $_POST["action"] == "delete_templates";
		}

	}
?>