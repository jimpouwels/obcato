<?php

namespace Obcato\Core\view\views;

use Obcato\Core\view\TemplateData;

class ImageLookup extends FormField {

    public function __construct(string $name, ?string $labelResourceIdentifier, ?string $value, bool $mandatory, bool $linkable, ?string $className) {
        parent::__construct($name, $value, $labelResourceIdentifier, $mandatory, $linkable, $className);
    }

    function getFormFieldTemplateFilename(): string {
        return "image_lookup.tpl";
    }

    function getFieldType(): string {
        return 'image_lookup';
    }

    function loadFormField(TemplateData $data) {

    }
}