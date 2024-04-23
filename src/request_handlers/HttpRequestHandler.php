<?php

namespace Obcato\Core\request_handlers;

use Obcato\Core\authentication\Session;
use Obcato\Core\core\BlackBoard;
use Obcato\Core\core\model\Notifications;

abstract class HttpRequestHandler {

    public function handle(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePost();
        } else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->handleGet();
        }
    }

    protected function sendSuccessMessage(string $message): void {
        Notifications::setSuccessMessage($message);
    }

    protected function sendErrorMessage(string $message): void {
        Notifications::setFailedMessage($message);
    }

    protected function redirectTo(string $url): void {
        header("Location: $url");
        exit();
    }

    protected function getTextResource(string $identifier): string {
        return Session::getTextResource($identifier);
    }

    protected function getBackendBaseUrl(): string {
        return BlackBoard::getBackendBaseUrl();
    }

    protected function isPostParam(string $name, string $value): string {
        return isset($_POST[$name]) && $_POST[$name] == $value;
    }

}