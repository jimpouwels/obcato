<?php

namespace Obcato\Core\database\dao;

interface ConfigDao {
    public function updateCaptchaSecret(string $captchaSecret): void;

    public function getCaptchaSecret(): ?string;
}