<?php

interface WebformDao {
    public function getWebForm(int $webform_id): ?WebForm;

    public function getAllWebForms(): array;

    public function persistWebForm(WebForm $webform): void;

    public function updateWebForm(WebForm $webform): void;

    public function deleteWebForm(WebForm $webform): void;

    public function persistWebFormItem(WebForm $webform, WebFormItem $webform_item): void;

    public function updateWebFormItem(WebFormItem $webform_item): void;

    public function deleteWebFormItem(int $item_id): void;

    public function getWebFormItem(int $id): ?WebFormItem;

    public function getWebFormItemsByWebForm(int $webform_id): array;

    public function addWebFormHandler(WebForm $webform, FormHandler $handler);

    public function getWebFormHandlersFor(WebForm $webform): array;

    public function deleteWebFormHandler(WebForm $webform, int $webform_handler_id): void;

    public function storeProperty(int $handler_id, WebFormHandlerProperty $property): void;

    public function deleteProperty(WebFormHandlerProperty $webform_handler_property): void;

    public function updateHandlerProperty(WebFormHandlerProperty $property): void;
}