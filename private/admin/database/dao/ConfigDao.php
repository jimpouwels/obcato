<?php

interface ConfigDao {
    public function updateCaptchaSecret(string $captchaSecret): void;

    public function getCaptchaSecret(): ?string;
}