<?php

namespace Obcato\Core\view\views;

use Obcato\Core\view\TemplateData;

class ImageLookup extends FormField {

    private ?string $contextId;

    public function __construct(string $name, ?string $labelResourceIdentifier, ?string $value, ?string $contextId, ?string $className) {
        parent::__construct($name, $value, $labelResourceIdentifier, false, false, $className);
        $this->contextId = $contextId;
    }

    function getFormFieldTemplateFilename(): string {
        return "image_lookup.tpl";
    }

    function getFieldType(): string {
        return 'image_lookup';
    }

    function loadFormField(TemplateData $data): void {
        $data->assign('contextId', $this->contextId);
    }
}