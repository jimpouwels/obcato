<?php

namespace Pageflow\Core\view\views;

use Pageflow\Core\view\TemplateData;

class FunctionalImageLookup extends FormField {

    private ?string $selectedTitle;

    public function __construct(
        string $name,
        ?string $labelResourceIdentifier,
        ?string $value,
        ?string $selectedTitle
    ) {
        parent::__construct($name, $value, $labelResourceIdentifier, false, false, null);
        $this->selectedTitle = $selectedTitle;
    }

    function getFormFieldTemplateFilename(): string {
        return "functional_image_lookup.tpl";
    }

    function getFieldType(): string {
        return 'functional_image_lookup';
    }

    function loadFormField(TemplateData $data): void {
        $data->assign('selected_title', $this->selectedTitle);
    }
}
