<?php

abstract class FormHandler {

    private FriendlyUrlManager $_friendly_url_manager;
    private array $_fields;
    private WebFormHandlerInstance $_webform_handler_instance;

    public function __construct() {
        $this->_friendly_url_manager = FriendlyUrlManager::getInstance();
    }

    abstract function getRequiredProperties(): array;

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

    protected function getFilledInPropertyValue(string $property_name): string {
        $property_value = $this->_webform_handler_instance->getProperty($property_name)->getValue();
        foreach ($this->_fields as $field) {
            $property_value = str_replace('${' . $field['name'] . '}', $field['value'], $property_value);
        }
        return $property_value;
    }


    protected function getProperty(string $property_name): string {
        return $this->_webform_handler_instance->getProperty($property_name)->getValue();
    }

    public function handlePost(WebFormHandlerInstance $webform_handler_instance, array $fields, Page $page, ?Article $article): void {
        $this->_fields = $fields;
        $this->_webform_handler_instance = $webform_handler_instance;
        $this->handle($fields, $page, $article);
    }

    abstract function handle(array $fields, Page $page, ?Article $article): void;
}

?>