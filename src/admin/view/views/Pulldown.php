<?php

namespace Obcato\Core\admin\view\views;

use Obcato\ComponentApi\TemplateData;

class Pulldown extends FormField {

    private array $_options;
    private bool $includeSelectIndication;

    public function __construct(string $name, string $label, ?string $value, array $options, bool $mandatory, ?string $className, bool $includeSelectIndication = false) {
        parent::__construct($name, $value, $label, $mandatory, false, $className);
        $this->includeSelectIndication = $includeSelectIndication;
        $this->_options = $options;
    }

    public function getFormFieldTemplateFilename(): string {
        return "system/form_pulldown.tpl";
    }

    public function loadFormField(TemplateData $data): void {
        $data->assign("options", $this->_options);
        $data->assign("include_select_indication", $this->includeSelectIndication);
    }

    public function addOption(string $name, string $value): void {
        $this->_options[] = array('name' => $name, 'value' => $value);
    }

    public function getFieldType(): string {
        return 'pulldown';
    }

}