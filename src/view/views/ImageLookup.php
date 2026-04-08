<?php

namespace Obcato\Core\view\views;

use Obcato\Core\view\TemplateData;

class ImageLookup extends FormField {

    private ?string $contextId;
    private bool $multipleImages;
    private ?string $getEndpoint;
    private ?string $updateEndpoint;
    private ?string $deleteEndpoint;
    private ?int $entityId;  // The actual entity ID for REST calls

    public function __construct(
        string $name, 
        ?string $labelResourceIdentifier, 
        ?string $value, 
        ?string $contextId, 
        ?string $className, 
        bool $multipleImages = true,
        ?string $getEndpoint = null,
        ?string $updateEndpoint = null,
        ?string $deleteEndpoint = null,
        ?int $entityId = null  // Optional: if different from contextId
    ) {
        parent::__construct($name, $value, $labelResourceIdentifier, false, false, $className);
        $this->contextId = $contextId;
        $this->multipleImages = $multipleImages;
        $this->getEndpoint = $getEndpoint;
        $this->updateEndpoint = $updateEndpoint;
        $this->deleteEndpoint = $deleteEndpoint;
        $this->entityId = $entityId ?? (is_numeric($contextId) ? (int)$contextId : null);
    }

    function getFormFieldTemplateFilename(): string {
        return "image_lookup.tpl";
    }

    function getFieldType(): string {
        return 'image_lookup';
    }

    function loadFormField(TemplateData $data): void {
        $data->assign('context_id', $this->contextId);
        $data->assign('entity_id', $this->entityId);
        $data->assign('multiple_images', $this->multipleImages);
        $data->assign('get_endpoint', $this->getEndpoint);
        $data->assign('update_endpoint', $this->updateEndpoint);
        $data->assign('delete_endpoint', $this->deleteEndpoint);
    }
}