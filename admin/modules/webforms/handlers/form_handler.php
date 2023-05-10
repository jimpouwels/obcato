<?php
    defined('_ACCESS') or die;
  
    abstract class FormHandler {

        private FriendlyUrlManager $_friendly_url_manager;

        public function __construct() {
            $this->_friendly_url_manager = FriendlyUrlManager::getInstance();
        }

        private array $_required_properties = array();

        abstract function getRequiredProperties(): array;

        abstract function handlePost(WebFormHandlerInstance $webform_handler_instance, array $fields): void;

        abstract function getNameResourceIdentifier(): string;

        abstract function getType(): string;

        protected function redirectTo(string $url): void {
            header("Location: $url");
            exit();
        }

        protected function getBackendBaseUrl(): string {
            return BlackBoard::getBackendBaseUrl();
        }

        protected function getPageUrl(Page $page): string {
            return $this->_friendly_url_manager->getFriendlyUrlForElementHolder($page);
        }

    }
?>