<?php
    defined("_ACCESS") or die;

    require_once CMS_ROOT . "view/views/page_picker.php";

    class GlobalSettingsPanel extends Panel {

        private Settings $_settings;

        public function __construct(Settings $settings) {
            parent::__construct('Algemene instellingen');
            $this->_settings = $settings;
        }

        public function getPanelContentTemplate(): string {
            return "modules/settings/global_settings_panel.tpl";
        }

        public function loadPanelContent(Smarty_Internal_Data $data): void {
            $current_homepage = $this->_settings->getHomepage();

            $website_title = new TextField("website_title", "settings_form_website_title_field", $this->_settings->getWebsiteTitle(), true, false, null);
            $email_field = new TextField("email_address", "settings_form_website_email_address_field", $this->_settings->getEmailAddress(), false, false, null);
            $homepage_picker = new PagePicker("homepage_page_id", "settings_form_website_homepage_field", $current_homepage->getId(), "apply_settings");

            $data->assign("website_title", $website_title->render());
            $data->assign("email_field", $email_field->render());

            if (!is_null($current_homepage)) {
                $data->assign("current_homepage_id", $current_homepage->getId());
                $data->assign("current_homepage_title", $current_homepage->getTitle());
            }
            $data->assign("homepage_picker", $homepage_picker->render());
        }
    }
