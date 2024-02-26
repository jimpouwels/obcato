<?php

namespace Obcato\Core\modules\webforms\handlers;

use Obcato\Core\friendly_urls\FriendlyUrlManager;
use Obcato\Core\modules\articles\model\Article;
use Obcato\Core\modules\pages\model\Page;
use Obcato\Core\modules\webforms\model\WebformHandlerInstance;
use Obcato\Core\core\BlackBoard;

abstract class FormHandler {

    private FriendlyUrlManager $friendlyUrlManager;
    private array $_fields;
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
        $property_value = $this->webformHandlerInstance->getProperty($property_name)->getValue();
        foreach ($this->_fields as $field) {
            $property_value = str_replace('${' . $field['name'] . '}', $field['value'], $property_value);
        }
        return $property_value;
    }


    protected function getProperty(string $property_name): string {
        return $this->webformHandlerInstance->getProperty($property_name)->getValue();
    }

    public function handlePost(WebFormHandlerInstance $webform_handler_instance, array $fields, Page $page, ?Article $article): void {
        $this->_fields = $fields;
        $this->webformHandlerInstance = $webform_handler_instance;
        $this->handle($fields, $page, $article);
    }

    abstract function handle(array $fields, Page $page, ?Article $article): void;
}