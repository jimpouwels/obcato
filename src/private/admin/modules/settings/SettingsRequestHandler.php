<?php
require_once CMS_ROOT . "/database/dao/SettingsDaoMysql.php";
require_once CMS_ROOT . "/request_handlers/HttpRequestHandler.php";
require_once CMS_ROOT . "/modules/settings/SettingsForm.php";

class SettingsRequestHandler extends HttpRequestHandler {

    private SettingsDao $settingsDao;
    private Settings $settings;

    public function __construct(Settings $settings) {
        $this->settingsDao = SettingsDaoMysql::getInstance();
        $this->settings = $settings;
    }

    public function handleGet(): void {}

    public function handlePost(): void {
        try {
            $settingsForm = new SettingsForm($this->settings);
            $settingsForm->loadFields();
            $this->settingsDao->update($this->settings);
            $this->settingsDao->setHomepage($settingsForm->getHomepageId());
            $this->sendSuccessMessage("Instellingen succesvol opgeslagen");
        } catch (FormException) {
            $this->sendErrorMessage("Instellingen niet opgeslagen, verwerk de fouten");
        }
    }
}