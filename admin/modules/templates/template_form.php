<?php

    defined("_ACCESS") or die;
    
    require_once CMS_ROOT . "core/form/form.php";
    require_once CMS_ROOT . "database/dao/template_dao.php";
    
    class TemplateForm extends Form {
    
        private Template $_template;
        private TemplateDao $_template_dao;
        private ?string $_path_to_uploaded_file = null;
        private bool $_is_file_uploaded;
        private ?string $_uploaded_file_name = null;
    
        public function __construct(Template $template) {
            $this->_template = $template;
            $this->_template_dao = TemplateDao::getInstance();
        }
    
        public function loadFields(): void {
            $this->_template->setName($this->getMandatoryFieldValue("name", "Naam is verplicht"));
            $this->_template->setScopeId($this->getMandatoryFieldValue("scope", "Scope is verplicht"));
            $this->_uploaded_file_name = $this->getUploadedFileName("template_file");
            $this->_path_to_uploaded_file = $this->getUploadFilePath("template_file");
            $this->_is_file_uploaded = $this->getUploadedFileName("template_file") != "";
            if ($this->_is_file_uploaded && !$this->fileExists()) {
                $this->_template->setFileName($this->_uploaded_file_name);
            } else {
                $this->_template->setFileName($this->getFieldValue("file_name"));
            }
            if ($this->hasErrors() || $this->fileNameExists()) {
                throw new FormException();
            }
            foreach ($this->_template->getTemplateVars() as $template_var) {
                $template_var_id = $template_var->getId();
                $template_var->setValue($this->getFieldValue("template_var_{$template_var_id}_field"));
                $this->_template_dao->updateTemplateVar($template_var);
            }
        }
        
        public function isFileUploaded(): bool {
            return $this->_is_file_uploaded;
        }
        
        public function getPathToUploadedFile(): string {
            return $this->_path_to_uploaded_file;
        }
        
        private function fileExists(): bool {
            if (file_exists(FRONTEND_TEMPLATE_DIR . "/" . $this->_uploaded_file_name) && !$this->uploadedFileIsCurrentTemplateFile()) {
                $this->raiseError("template_file", "Er bestaat al een ander template met dezelfde naam");
                return true;
            } else {
                return false;
            }
        }
        
        private function uploadedFileIsCurrentTemplateFile(): bool {
            return $this->_uploaded_file_name == $this->_template->getFileName();
        }
        
        private function fileNameExists(): void {
            $existing_template = $this->_template_dao->getTemplateByFileName($this->_template->getFileName());
            if (!is_null($existing_template) && $existing_template->getId() != $this->_template->getId()) {
                $this->raiseError("file_name_error", "Deze bestandsnaam bestaat al voor een ander template");
            }
        }
    
    }
    