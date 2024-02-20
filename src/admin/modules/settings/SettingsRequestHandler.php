<?php

namespace Obcato\Core\admin\modules\settings;

use Obcato\Core\admin\core\form\FormException;
use Obcato\Core\admin\database\dao\SettingsDao;
use Obcato\Core\admin\database\dao\SettingsDaoMysql;
use Obcato\Core\admin\modules\settings\model\Settings;
use Obcato\Core\admin\request_handlers\HttpRequestHandler;

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