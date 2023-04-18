<?php
    defined("_ACCESS") or die;

    require_once CMS_ROOT . "view/views/page_picker.php";

    class GlobalSettingsPanel extends Panel {

        private static string $TEMPLATE = "modules/settings/global_settings_panel.tpl";
        private Settings $_settings;

        public function __construct(Settings $settings) {
            parent::__construct('Algemene instellingen');
            $this->_settings = $settings;
        }

        public function render(): string {
            return parent::render();
        }

        public function renderPanelContent(): string {
            $current_homepage = $this->_settings->getHomepage();

            $website_title = new TextField("website_title", "Website titel", $this->_settings->getWebsiteTitle(), true, false, null);
            $email_field = new TextField("email_address", "Email adres", $this->_settings->getEmailAddress(), false, false, null);
            $homepage_picker = new PagePicker("Homepage", $current_homepage->getId(), "homepage_page_id", "Selecteer pagina", "apply_settings", "pick_homepage");

            $this->getTemplateEngine()->assign("website_title", $website_title->render());
            $this->getTemplateEngine()->assign("email_field", $email_field->render());

            if (!is_null($current_homepage)) {
                $this->getTemplateEngine()->assign("current_homepage_id", $current_homepage->getId());
                $this->getTemplateEngine()->assign("current_homepage_title", $current_homepage->getTitle());
            }
            $this->getTemplateEngine()->assign("homepage_picker", $homepage_picker->render());
            return $this->getTemplateEngine()->fetch(self::$TEMPLATE);
        }
    }
