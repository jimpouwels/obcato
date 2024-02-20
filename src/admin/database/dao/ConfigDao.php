<?php

namespace Obcato\Core\admin\database\dao;

interface ConfigDao {
    public function updateCaptchaSecret(string $captchaSecret): void;

    public function getCaptchaSecret(): ?string;
}