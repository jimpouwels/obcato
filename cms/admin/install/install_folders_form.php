<?php
    DEFINED("_ACCESS") or die;

    require_once "pre_handlers/form.php";

    class InstallFoldersForm extends Form {

        public $_frontend_templates_dir;
        public $_backend_templates_dir;
        public $_backend_static_files_dir;
        public $_config_dir;
        public $_upload_dir;
        public $_component_dir;
        private $_settings;

        public function __construct($settings) {
            $this->_settings = $settings;
        }

        public function loadFields()
        {
            $this->_frontend_templates_dir = $this->getMandatoryFieldValue("frontend_template_dir", "Dit veld is verplicht");
            $this->_backend_templates_dir = $this->getMandatoryFieldValue("backend_template_dir", "Dit veld is verplicht");
            $this->_backend_static_files_dir = $this->getMandatoryFieldValue("backend_static_files_dir", "Dit veld is verplicht");
            $this->_config_dir = $this->getMandatoryFieldValue("config_dir", "Dit veld is verplicht");
            $this->_upload_dir = $this->getMandatoryFieldValue("upload_dir", "Dit veld is verplicht");
            $this->_component_dir = $this->getMandatoryFieldValue("component_dir", "Dit veld is verplicht");
            if ($this->hasErrors())
            {
                throw new FormException();
            }
            else {
                $this->_settings->setFrontendTemplateDir($this->preserveBackSlashes($this->_frontend_templates_dir));
                $this->_settings->setStaticDir($this->preserveBackSlashes($this->_backend_static_files_dir));
                $this->_settings->setConfigDir($this->preserveBackSlashes($this->_config_dir));
                $this->_settings->setUploadDir($this->preserveBackSlashes($this->_upload_dir));
                $this->_settings->setBackendTemplateDir($this->preserveBackSlashes($this->_backend_templates_dir));
                $this->_settings->setComponentDir($this->preserveBackSlashes($this->_component_dir));
            }
        }

        public function getFrontendTemplatesDir() {
            return $this->_frontend_templates_dir;
        }

        public function getBackendTemplatesDir() {
            return $this->_backend_templates_dir;
        }

        public function getBackendStaticFilesDir() {
            return $this->_backend_static_files_dir;
        }

        public function getConfigDir() {
            return $this->_config_dir;
        }

        public function getUploadDir() {
            return $this->_upload_dir;
        }

        public function getComponentDir() {
            return $this->_component_dir;
        }

        private function preserveBackSlashes($value) {
            return str_replace("\\", "\\\\", $value);
        }

    }
?>