<?php

namespace Obcato\Core\admin\database\dao;

use Obcato\Core\admin\modules\settings\model\Settings;

interface SettingsDao {
    public function update(Settings $settings): void;

    public function insert(Settings $settings): void;

    public function getSettings(): ?Settings;

    public function setHomepage(int $homepageId): void;
}