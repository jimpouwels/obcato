<?php

	defined("_ACCESS") or die;
	
	require_once CMS_ROOT . "/pre_handlers/form.php";
	require_once CMS_ROOT . "/database/dao/template_dao.php";
	require_once CMS_ROOT . "/database/dao/settings_dao.php";
	
	class TemplateForm extends Form {
	
		private $_template;
		private $_template_dao;
		private $_template_dir;
		private $_path_to_uploaded_file;
		private $_is_file_uploaded;
	
		public function __construct($template) {
			$this->_template = $template;
			$this->_template_dao = TemplateDao::getInstance();
			$this->_template_dir = SettingsDao::getInstance()->getSettings()->getFrontendTemplateDir();
		}
	
		public function loadFields() {
			$this->_template->setName($this->getMandatoryFieldValue("name", "Naam is verplicht"));
			$this->_template->setFileName($this->getFieldValue("file_name"));
			$this->_template->setScopeId($this->getMandatoryFieldValue("scope", "Scope is verplicht"));
			$this->_uploaded_file_name = $this->getUploadedFileName("template_file");
			$this->_path_to_uploaded_file = $this->getUploadFilePath("template_file");
			$this->_is_file_uploaded = $this->getUploadedFileName("template_file") != "";
			if ($this->_is_file_uploaded && !$this->fileExists()) {
				$this->_template->setFileName($this->_uploaded_file_name);
			}
			if ($this->hasErrors() || $this->fileNameExists())
				throw new FormException();
		}
		
		public function isFileUploaded() {
			return $this->_is_file_uploaded;
		}
		
		public function getPathToUploadedFile() {
			return $this->_path_to_uploaded_file;
		}
		
		private function fileExists() {
			echo $this->_template_dir . "/" . $this->_uploaded_file_name;
			if (file_exists($this->_template_dir . "/" . $this->_uploaded_file_name) && !$this->uploadedFileIsCurrentTemplateFile()) {
				$this->raiseError("template_file", "Er bestaat al een ander template met dezelfde naam");
				return true;
			} else {
				return false;
			}
		}
		
		private function uploadedFileIsCurrentTemplateFile() {
			return $this->_uploaded_file_name == $this->_template->getFileName();
		}
		
		private function fileNameExists() {
			$existing_template = $this->_template_dao->getTemplateByFileName($this->_template->getFileName());
			if (!is_null($existing_template) && $existing_template->getId() != $this->_template->getId()) {
				$this->raiseError("file_name_error", "Deze bestandsnaam bestaat al voor een ander template");
			}
		}
	
	}
	