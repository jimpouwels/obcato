<?php

namespace Pageflow\Core\modules\webforms\handlers;

use Pageflow\Core\friendly_urls\FriendlyUrlManager;
use Pageflow\Core\modules\articles\model\Article;
use Pageflow\Core\modules\pages\model\Page;
use Pageflow\Core\modules\webforms\model\WebformHandlerInstance;
use Pageflow\Core\core\BlackBoard;

abstract class FormHandler {

    private FriendlyUrlManager $friendlyUrlManager;
    private array $fields;
    private WebFormHandlerInstance $webformHandlerInstance;

    public function __construct() {
        $this->friendlyUrlManager = FriendlyUrlManager::getInstance();
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
        return $this->friendlyUrlManager->getFriendlyUrlForElementHolder($page);
    }

    protected function getFilledInPropertyValue(string $property_name): string {
        $propertyValue = $this->webformHandlerInstance->getProperty($property_name)->getValue();
        foreach ($this->fields as $field) {
            $propertyValue = str_replace('${' . $field['name'] . '}', $field['value'], $propertyValue);
            $propertyValue = str_replace('${url}', $this->createBaseUrl() . $_SERVER['REQUEST_URI'], $propertyValue);
        }
        return $propertyValue;
    }


    protected function getProperty(string $property_name): string {
        return $this->webformHandlerInstance->getProperty($property_name)->getValue();
    }

    public function handlePost(WebFormHandlerInstance $webform_handler_instance, array $fields, Page $page, ?Article $article): void {
        $this->fields = $fields;
        $this->webformHandlerInstance = $webform_handler_instance;
        $this->handle($fields, $page, $article);
    }

    private function createBaseUrl(): string {
        $baseUrl = 'https://';
        $baseUrl .= $_SERVER['HTTP_HOST'];
        return $baseUrl;
    }

    abstract function handle(array $fields, Page $page, ?Article $article): void;
}