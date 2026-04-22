<?php

namespace Pageflow\Core\modules\settings;

use Pageflow\Core\core\form\FormException;
use Pageflow\Core\database\dao\SettingsDao;
use Pageflow\Core\database\dao\SettingsDaoMysql;
use Pageflow\Core\modules\settings\model\Settings;
use Pageflow\Core\request_handlers\HttpRequestHandler;

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