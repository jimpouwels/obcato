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
        return null;
    }

    public function setHomepage(int $homepageId): void {
        // TODO: Implement setHomepage() method.
    }
}