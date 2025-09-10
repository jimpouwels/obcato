<?php

namespace Obcato\Core\view\views;

use Obcato\Core\authentication\Session;
use Obcato\Core\utilities\StringUtility;
use Obcato\Core\view\TemplateData;

abstract class FormField extends Visual {

    private ?string $cssClass;
    private string $fieldName;
    private ?string $labelResourceIdentifier;
    private bool $mandatory;
    private bool $linkable;
    private ?string $value;
    private ?string $helpText;

    protected function __construct(string $field_name, ?string $value, ?string $labelResourceIdentifier, bool $mandatory, bool $linkable, ?string $cssClass, ?string $helpTextResourceIdentifier = "") {
        parent::__construct();
        $this->fieldName = $field_name;
        $this->cssClass = $cssClass;
        $this->labelResourceIdentifier = $labelResourceIdentifier;
        $this->mandatory = $mandatory;
        $this->linkable = $linkable;
        $this->helpText = $this->getTextResource($helpTextResourceIdentifier);
        $this->value = $value;
    }

    public function getTemplateFilename(): string {
        return "form_field.tpl";
    }

    abstract function getFormFieldTemplateFilename(): string;

    abstract function getFieldType(): string;

    abstract function loadFormField(TemplateData $data);

    public function load(): void {
        $this->assign("error", $this->getErrorHtml($this->fieldName));
        $this->assign('label', $this->getLabelHtml());
        $this->assign('type', $this->getFieldType());
        $this->assign('help_text', $this->helpText);

        $fieldTemplateData = $this->createChildData();
        $this->loadFormField($fieldTemplateData);
        $fieldTemplateData->assign('classes', $this->getCssClassesHtml());
        $fieldTemplateData->assign("field_name", $this->fieldName);
        $fieldTemplateData->assign("field_value", $this->getFieldValue());
        $this->assign('form_field', $this->getTemplateEngine()->fetch($this->getFormFieldTemplateFilename(), $fieldTemplateData));
    }

    public function getErrorHtml(string $fieldName): string {
        $errorHtml = "";
        if (Session::hasError($fieldName)) {
            $error = new FormError(Session::popError($fieldName));
            return $error->render();
        }
        return $errorHtml;
    }

    public function getCssClassesHtml(): string {
        $cssClassHtml = $this->cssClass;
        $cssClassHtml .= ' ' . $this->errorClass($this->fieldName);
        if ($this->linkable) {
            $cssClassHtml .= 'linkable ';
        }
        return trim($cssClassHtml);
    }

    public function errorClass(string $field_name): string {
        if (Session::hasError($field_name)) {
            return "invalid ";
        }
        return "";
    }

    protected function getInputLabelHtml(string $labelResourceIdentifier, string $fieldName, bool $mandatory): string {
        if ($labelResourceIdentifier) {
            $label = new FormLabel($fieldName, $labelResourceIdentifier, $mandatory);
            return $label->render();
        }
        return "";
    }

    protected function getFieldName(): string {
        return $this->fieldName;
    }

    private function getFieldValue(): ?string {
        return StringUtility::escapeXml($this->value);
    }

    private function getLabelHtml(): ?string {
        if ($this->labelResourceIdentifier) {
            return $this->getInputLabelHtml($this->labelResourceIdentifier, $this->fieldName, $this->mandatory);
        } else {
            return null;
        }
    }
}