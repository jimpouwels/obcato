<?php

require_once(CMS_ROOT . '/database/dao/SettingsDao.php');

class SettingsDaoMock implements SettingsDao {

    public function update(Settings $settings): void {
        // TODO: Implement update() method.
    }

    public function insert(Settings $settings): void {
        // TODO: Implement insert() method.
    }

    public function getSettings(): ?Settings {
        $settings = new Settings();
        $settings->setPublicRootDir(__DIR__);
        return $settings;
    }

    public function setHomepage(int $homepage_id): void {
        // TODO: Implement setHomepage() method.
    }
}