<?php

    defined("_ACCESS") or die;
    
    require_once CMS_ROOT . "core/form/form.php";
    require_once CMS_ROOT . "database/dao/template_dao.php";
    
    class TemplateEditorForm extends Form {
    
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
            $this->_template->setTemplateFileId($this->getFieldValue("template_editor_template_file"));
            $this->_uploaded_file_name = $this->getUploadedFileName("template_file");
            $this->_path_to_uploaded_file = $this->getUploadFilePath("template_file");
            $this->_is_file_uploaded = $this->getUploadedFileName("template_file") != "";
            if ($this->hasErrors()) {
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
        
    }
    