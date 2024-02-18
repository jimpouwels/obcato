<?php

interface SettingsDao {
    public function update(Settings $settings): void;

    public function insert(Settings $settings): void;

    public function getSettings(): ?Settings;

    public function setHomepage(int $homepageId): void;
}