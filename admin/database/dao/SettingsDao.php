<?php
defined('_ACCESS') or die;

interface SettingsDao {
    public function update(Settings $settings): void;

    public function insert(Settings $settings): void;

    public function getSettings(): ?Settings;

    public function setHomepage(int $homepage_id): void;
}