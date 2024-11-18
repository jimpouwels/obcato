<?php

namespace Obcato\Core\view\views;

use Obcato\Core\view\TemplateData;

class Pulldown extends FormField {

    private array $options;
    private bool $includeLinkSelectIndication;

    public function __construct(string $name, string $label, ?string $value, array $options, bool $mandatory, ?string $className, bool $includeLinkSelectIndication = false) {
        parent::__construct($name, $value, $label, $mandatory, false, $className);
        $this->includeLinkSelectIndication = $includeLinkSelectIndication;
        $this->options = $options;
    }

    public function getFormFieldTemplateFilename(): string {
        return "system/form_pulldown.tpl";
    }

    public function loadFormField(TemplateData $data): void {
        $data->assign("options", $this->options);
        $data->assign("include_select_indication", $this->includeLinkSelectIndication);
    }

    public function addOption(string $name, string $value): void {
        $this->options[] = array('name' => $name, 'value' => $value);
    }

    public function getFieldType(): string {
        return 'pulldown';
    }

}