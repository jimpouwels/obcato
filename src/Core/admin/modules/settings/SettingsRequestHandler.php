<?php

namespace Obcato\Core;

use Obcato\Core\admin\core\form\FormException;

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