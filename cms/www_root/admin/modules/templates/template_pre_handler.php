<?php
	// No direct access
	defined('_ACCESS') or die;

	require_once "database/dao/template_dao.php";
	require_once "libraries/validators/form_validator.php";
	require_once "libraries/handlers/form_handler.php";
	require_once "libraries/system/notifications.php";
	require_once "view/request_handlers/module_request_handler.php";
	require_once "libraries/system/notifications.php";
	
	class TemplatePreHandler extends ModuleRequestHandler {
	
		private static $TEMPLATE_ID_GET = "template";
		private static $TEMPLATE_ID_POST = "template_id";

		private $_template_dao;
		private $_current_template;
		
		public function __construct() {
			$this->_template_dao = TemplateDao::getInstance();
		}
	
		public function handleGet() {
			if ($this->isCurrentTemplateShown()) {
				$this->_current_template = $this->getTemplateFromGetRequest();
			}
		}
		
		public function handlePost() {
			$this->_current_template = $this->getTemplateFromPostRequest();
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
		
		private function addTemplate() {
			$new_template = $this->_template_dao->createTemplate();
			Notifications::setSuccessMessage("Template succesvol aangemaakt");
			header('Location: /admin/index.php?template=' . $new_template->getId());
			exit();
		}
		
		private function deleteTemplates() {
			foreach ($this->_template_dao->getTemplates() as $template) {
				if (isset($_POST['template_' . $template->getId() . '_delete'])) {
					$this->_template_dao->deleteTemplate($template);
				}
			}
			Notifications::setSuccessMessage("Template(s) succesvol verwijderd");
		}
		
		private function updateTemplate() {
			if (isset($this->_current_template) && !is_null($this->_current_template)) {
				// get the template dir
				$template_dir = Settings::find()->getFrontendTemplateDir();
			
				$name = FormValidator::checkEmpty("name", "Naam is verplicht");
				$file_name = FormHandler::getFieldValue("file_name", "Bestandsnaam is verplicht");
				
				// check if the filename does not exist already
				if ($file_name != $this->_current_template->getFileName() && !is_uploaded_file($_FILES["template_file"]["tmp_name"])) {
					$check_template = $this->_template_dao->getTemplateByFileName($file_name);
					if (!is_null($check_template)) {
						$this->setRequestError("file_name_error", "Deze bestandsnaam bestaat al voor een ander template");
					}
				}
				// check if the uploaded file already exists
				if (is_uploaded_file($_FILES["template_file"]["tmp_name"])) {
					// check if the filename does not exist yet for another template
					if (file_exists($template_dir . "/" . $_FILES["template_file"]["name"]) && $this->_current_template->getName() != $_FILES["template_file"]["name"]) {
						$this->setRequestError("template_file_error", "Er bestaat al een ander template met dezelfde naam");
					}
				}
				$scopeId = FormValidator::checkEmpty("scope", "Scope is verplicht");
				if ($this->getErrorCount() == 0) {
					// check uploaded template file
					$old_file_name = $template_dir . "/" . $this->_current_template->getFileName();
					if (is_uploaded_file($_FILES["template_file"]["tmp_name"])) {
						// first delete the old file
						if (file_exists($old_file_name) && $this->_current_template->getFileName() != "") {
							unlink($old_file_name);
						}
						move_uploaded_file($_FILES["template_file"]["tmp_name"], $template_dir . "/" . $_FILES["template_file"]["name"]);
						$this->_current_template->setFileName($_FILES["template_file"]["name"]);
					} else if ($file_name != '') {
						// rename the file
						if ($this->_current_template->getFileName() != "" && file_exists($old_file_name)) {
							rename($template_dir . "/" . $this->_current_template->getFileName(), $template_dir . "/" . $file_name);
						}
						
						$this->_current_template->setFileName($file_name);
					}
				
					$this->_current_template->setName($name);
					$this->_current_template->setScopeId($scopeId);
					
					$this->_template_dao->updateTemplate($this->_current_template);

					Notifications::setSuccessMessage("Template succesvol opgeslagen");
				} else {
					Notifications::setFailedMessage("Template niet opgeslagen, verwerk de fouten");
				}
			}
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
			return isset($_POST["action"]) && $_POST["action"] == "delete_template";
		}

	}
?>