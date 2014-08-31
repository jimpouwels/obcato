<?php
    DEFINED("_ACCESS") or die;

    require_once "pre_handlers/form.php";

    class InstallFoldersForm extends Form {

        public function loadFields()
        {
            $frontend_templates = $this->getMandatoryFieldValue("frontend_template_dir", "Dit veld is verplicht");
            $backend_root_dir = $this->getMandatoryFieldValue("backend_root_dir", "Dit veld is verplicht");
            $backend_static_files_dir = $this->getMandatoryFieldValue("backend_static_files_dir", "Dit veld is verplicht");
            $config_dir = $this->getMandatoryFieldValue("config_dir", "Dit veld is verplicht");
            $upload_dir = $this->getMandatoryFieldValue("upload_dir", "Dit veld is verplicht");
            $backend_template_dir = $this->getMandatoryFieldValue("backend_template_dir", "Dit veld is verplicht");
            $component_dir = $this->getMandatoryFieldValue("component_dir", "Dit veld is verplicht");
            if ($this->hasErrors())
                throw new FormException();
        }
    }
?>