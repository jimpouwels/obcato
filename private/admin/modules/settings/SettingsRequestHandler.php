<?php
require_once CMS_ROOT . "/database/dao/SettingsDaoMysql.php";
require_once CMS_ROOT . "/request_handlers/HttpRequestHandler.php";
require_once CMS_ROOT . "/modules/settings/SettingsForm.php";

class SettingsRequestHandler extends HttpRequestHandler {

    private SettingsDao $settingsDao;

    public function __construct() {
        $this->settingsDao = SettingsDaoMysql::getInstance();
    }

    public function handleGet(): void {}

    public function handlePost(): void {
        try {
            $settings = $this->settingsDao->getSettings();
            $settingsForm = new SettingsForm($settings);
            $settingsForm->loadFields();
            $this->settingsDao->update($settings);
            $this->settingsDao->setHomepage($settingsForm->getHomepageId());
            $this->sendSuccessMessage("Instellingen succesvol opgeslagen");
        } catch (FormException) {
            $this->sendErrorMessage("Instellingen niet opgeslagen, verwerk de fouten");
        }
    }
}