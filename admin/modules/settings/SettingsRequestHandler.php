<?php

defined("_ACCESS") or die;

require_once CMS_ROOT . "/database/dao/SettingsDaoMysql.php";
require_once CMS_ROOT . "/request_handlers/HttpRequestHandler.php";
require_once CMS_ROOT . "/modules/settings/SettingsForm.php";

class SettingsRequestHandler extends HttpRequestHandler {

    private SettingsDao $_settings_dao;

    public function __construct() {
        $this->_settings_dao = SettingsDaoMysql::getInstance();
    }

    public function handleGet(): void {}

    public function handlePost(): void {
        $settings = $this->_settings_dao->getSettings();
        $settings_form = new SettingsForm($settings);
        try {
            $settings_form->loadFields();
            $this->_settings_dao->update($settings);
            $this->_settings_dao->setHomepage($settings_form->getHomepageId());
            $this->sendSuccessMessage("Instellingen succesvol opgeslagen");
        } catch (FormException $e) {
            $this->sendErrorMessage("Instellingen niet opgeslagen, verwerk de fouten");
        }
    }
}

?>