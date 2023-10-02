<?php
defined("_ACCESS") or die;

class DirectorySettingsPanel extends Panel {

    private Settings $_settings;

    public function __construct(Settings $settings) {
        parent::__construct('Directory instellingen');
        $this->_settings = $settings;
    }

    public function getPanelContentTemplate(): string {
        return "modules/settings/directory_settings_panel.tpl";
    }

    public function loadPanelContent(Smarty_Internal_Data $data): void {
        $cms_root_dir = new TextField("cms_root_dir", "settings_form_backend_cms_root_dir_field", $this->_settings->getCmsRootDir(), true, false, null);
        $data->assign("cms_root_dir", $cms_root_dir->render());

        $public_root_dir = new TextField("public_root_dir", "settings_form_backend_public_root_dir_field", $this->_settings->getPublicRootDir(), true, false, null);
        $data->assign("public_root_dir", $public_root_dir->render());

        $static_dir = new TextField("static_dir", "settings_form_backend_static_dir_field", $this->_settings->getStaticDir(), true, false, null);
        $data->assign("static_dir", $static_dir->render());

        $config_dir = new TextField("config_dir", "settings_form_backend_config_dir_field", $this->_settings->getConfigDir(), true, false, null);
        $data->assign("config_dir", $config_dir->render());

        $upload_dir = new TextField("upload_dir", "settings_form_backend_upload_dir_field", $this->_settings->getUploadDir(), true, false, null);
        $data->assign("upload_dir", $upload_dir->render());

        $frontend_template_dir = new TextField("frontend_template_dir", "settings_form_backend_frontend_template_dir_field", $this->_settings->getFrontendTemplateDir(), true, false, null);
        $data->assign("frontend_template_dir", $frontend_template_dir->render());

        $backend_template_dir = new TextField("backend_template_dir", "settings_form_backend_backend_template_dir_field", $this->_settings->getBackendTemplateDir(), true, false, null);
        $data->assign("backend_template_dir", $backend_template_dir->render());

        $component_dir = new TextField("component_dir", "settings_form_backend_component_dir_field", $this->_settings->getComponentDir(), true, false, null);
        $data->assign("component_dir", $component_dir->render());
    }

}

?>
