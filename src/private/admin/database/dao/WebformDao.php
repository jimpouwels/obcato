<?php

interface WebformDao {
    public function getWebForm(int $webformId): ?WebForm;

    public function getAllWebForms(): array;

    public function persistWebForm(WebForm $webform): void;

    public function updateWebForm(WebForm $webform): void;

    public function deleteWebForm(WebForm $webform): void;

    public function persistWebFormItem(WebForm $webform, WebFormItem $webformItem): void;

    public function updateWebFormItem(WebFormItem $webformItem): void;

    public function deleteWebFormItem(int $itemId): void;

    public function getWebFormItem(int $id): ?WebFormItem;

    public function getWebFormItemsByWebForm(int $webformId): array;

    public function addWebFormHandler(WebForm $webform, FormHandler $handler);

    public function getWebFormHandlersFor(WebForm $webform): array;

    public function deleteWebFormHandler(WebForm $webform, int $webformHandlerId): void;

    public function storeProperty(int $handler_id, WebFormHandlerProperty $property): void;

    public function deleteProperty(WebFormHandlerProperty $webFormHandlerProperty): void;

    public function updateHandlerProperty(WebFormHandlerProperty $property): void;
}