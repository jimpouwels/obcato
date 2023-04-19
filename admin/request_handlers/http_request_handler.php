<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'core/notifications.php';
    
    abstract class HttpRequestHandler {
    
        public function handle(): void {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->handlePost();
            } else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $this->handleGet();
            }
        }
    
        abstract function handleGet(): void;
        
        abstract function handlePost(): void;

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

    }