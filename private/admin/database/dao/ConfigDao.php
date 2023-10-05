<?php

interface ConfigDao {
    public function updateCaptchaSecret(string $captcha_secret): void;

    public function getCaptchaSecret(): ?string;
}